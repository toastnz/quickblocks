<?php

namespace Toast\QuickBlocks;

use SilverStripe\Control\Director;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\GridField\GridFieldConfig_Base;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DB;
use SilverStripe\Versioned\Versioned;
use SilverStripe\ORM\DataObject;

/**
 * Class QuickBlock
 *
 * @property string Title
 */
class QuickBlock extends DataObject
{
    private static $singular_name = 'Block';
    private static $plural_name = 'Blocks';
    private static $table_name = 'QuickBlock';

    private static $db = [
        'Title' => 'Varchar(255)'
    ];

    private static $casting = [
        'Icon' => 'HTMLText'
    ];

    private static $summary_fields = [
        'IconForCMS'     => 'Type',
        'Title'          => 'Title',
        'ContentSummary' => 'Content'
    ];

    private static $extensions = [
        Versioned::class
    ];

    private static $versioned_gridfield_extensions = true;

    public function getIconForCMS()
    {
        // Check if this class has an icon set
        $statIcon = self::config()->get('icon');

        if (!empty($statIcon) && file_exists(Director::baseFolder() . '/' . $statIcon)) {
            return DBField::create_field('HTMLText', '<img src="' . $statIcon . '">');
        }

        $path = TOAST_QUICKBLOCKS_DIR . '/images/';
        $icon = $path . strtolower($this->i18n_singular_name()) . '.png';

        if (!file_exists(Director::baseFolder() . '/' . $icon)) {
            $icon = $path . 'text.png';
        }

        return DBField::create_field('HTMLText', '<img src="' . $icon . '">');
    }

    public function IconForCMS()
    {
        return $this->getIconForCMS();
    }

    function forTemplate()
    {
        return $this->renderWith([$this->ClassName, 'Toast\QuickBlocks\QuickBlock']);
    }

    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldToTab('Root.Main', TextField::create('Title', 'Name')
            ->setAttribute('placeholder', 'This is a helper field only (will not show in templates)'));

        $this->extend('updateCMSFields', $fields);

        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field(DBHTMLText::class, '');
    }

    public function getTitle()
    {
        if ($this->ID) {
            return $this->getField('Title') ?: $this->i18n_singular_name();
        } else {
            return $this->getField('Title');
        }
    }

    /**
     * For use in modals etc
     *
     * @return String
     */
    public function getApiURL()
    {
        return Controller::join_links(Controller::curr()->AbsoluteLink(), 'QuickBlock', $this->ID);
    }

    /* ==========================================
     * CRUD
     * ========================================*/

    public function canView($member = null)
    {
        if ($member && Permission::checkMember($member, ["ADMIN", "SITETREE_VIEW_ALL"])) {
            return true;
        }

        $extended = $this->extendedCan('canView', $member);

        if ($extended !== null) {
            return $extended;
        }

        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDeleteFromLive($member = null)
    {
        // Standard mechanism for accepting permission changes from extensions
        $extended = $this->extendedCan('canDeleteFromLive', $member);

        if ($extended !== null) {
            return $extended;
        }

        return $this->canPublish($member);
    }

    /**
     * This function should return true if the current user can publish this page. It can be overloaded to customise
     * the security model for an application.
     *
     * Denies permission if any of the following conditions is true:
     * - canPublish() on any extension returns false
     * - canEdit() returns false
     *
     * @uses SiteTreeExtension->canPublish()
     *
     * @param Member $member
     * @return bool True if the current user can publish this page.
     */
    public function canPublish($member = null)
    {
        if (!$member || !(is_a($member, Member::class)) || is_numeric($member)) {
            $member = Member::currentUser();
        }

        if ($member && Permission::checkMember($member, "ADMIN")) {
            return true;
        }

        // Standard mechanism for accepting permission changes from extensions
        $extended = $this->extendedCan('canPublish', $member);
        if ($extended !== null) {
            return $extended;
        }

        // Normal case - fail over to canEdit()
        return $this->canEdit($member);
    }

    /* ==========================================
    * VERSIONING
    * ========================================*/

    /**
     * Check if this document has been published.
     *
     * @return bool
     */
    public function isPublished()
    {
        if ($this->isNew()) {
            return false;
        }

        return (DB::prepared_query("SELECT \"ID\" FROM \"QuickBlock_Live\" WHERE \"ID\" = ?", [$this->ID])->value())
            ? true
            : false;
    }

    /**
     * Check if this page is new - that is, if it has yet to have been written to the database.
     *
     * @return bool
     */
    public function isNew()
    {
        /**
         * This check was a problem for a self-hosted site, and may indicate a bug in the interpreter on their server,
         * or a bug here. Changing the condition from empty($this->ID) to !$this->ID && !$this->record['ID'] fixed this.
         */
        if (empty($this->ID)) {
            return true;
        }

        if (is_numeric($this->ID)) {
            return false;
        }

        return stripos($this->ID, 'new') === 0;
    }

    /**
     * Removes the page from both live and stage
     *
     * @return bool Success
     */
    public function doArchive()
    {
        $this->invokeWithExtensions('onBeforeArchive', $this);

        $thisID = $this->ID;

        if ($this->doUnpublish()) {
            $this->delete();

            // Remove from join tables
            DB::prepared_query("DELETE FROM \"Page_ContentBlocks\" WHERE \"QuickBlockID\" = ?", [$thisID]);

            $this->invokeWithExtensions('onAfterArchive', $this);

            return true;
        }

        return false;
    }

    /**
     * Check if the current user is allowed to archive this page.
     * If extended, ensure that both canDelete and canDeleteFromLive are extended also
     *
     * @param Member $member
     * @return bool
     */
    public function canArchive($member = null)
    {
        if (!$member) {
            $member = Member::currentUser();
        }

        // Standard mechanism for accepting permission changes from extensions
        $extended = $this->extendedCan('canArchive', $member);
        if ($extended !== null) {
            return $extended;
        }

        // Check if this page can be deleted
        if (!$this->canDelete($member)) {
            return false;
        }

        // If published, check if we can delete from live
        if ($this->ExistsOnLive && !$this->canDeleteFromLive($member)) {
            return false;
        }

        return true;
    }


    /**
     * Unpublish this page - remove it from the live site
     *
     * @uses DocumentExtension->onBeforeUnpublish()
     * @uses DocumentExtension->onAfterUnpublish()
     */
//    public function doUnpublish()
//    {
//        if (!$this->canDeleteFromLive()) {
//            return false;
//        }
//        if (!$this->ID) {
//            return false;
//        }
//
//        $this->invokeWithExtensions('onBeforeUnpublish', $this);
//
//        $origStage = Versioned::get_reading_mode();
//        Versioned::set_reading_mode(Versioned::LIVE);
//
//        // This way our ID won't be unset
//        $clone = clone $this;
//
//        $clone->deleteFromStage(Versioned::LIVE);
//        $clone->delete();
//
//
//        Versioned::set_reading_mode($origStage);
//
//        // If we're on the draft site, then we can update the status.
//        // Otherwise, these lines will resurrect an inappropriate record
////        if (DB::prepared_query("SELECT \"ID\" FROM \"QuickBlock_Live\" WHERE \"ID\" = ?", [$this->ID])->value()
////            && Versioned::get_reading_mode() != 'Live') {
////            $this->write();
////        }
//
//        $this->invokeWithExtensions('onAfterUnpublish', $this);
//
//        return true;
//    }
}

