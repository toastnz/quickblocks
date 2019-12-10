<?php

namespace Toast\QuickBlocks;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Permission;

/**
 * Class ContentTab
 * @package Toast\QuickBlocks
 *
 * @method TabbedContentBlock Parent()
 * @method File Icon()
 */
class ContentTab extends DataObject
{
    private static $singular_name = 'Tab';
    private static $plural_name = 'Tabs';
    private static $default_sort = 'SortOrder';
    private static $table_name = 'ContentTab';

    private static $db = [
        'Title'       => 'Varchar(255)',
        'Content'     => 'HTMLText',
        'Description' => 'Text',
        'SortOrder'   => 'Int'
    ];

    private static $has_one = [
        'Parent' => TabbedContentBlock::class
    ];

    private static $summary_fields = [
        'Title'           => 'Heading',
        // 'Description'     => 'Description',
        'ContentSummary' => 'Content'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {

        $fields = FieldList::create([
            TextField::create('Title', 'Title'),
            // TextareaField::create('Description', 'Description')
            //     ->setRows(2),
            HTMLEditorField::create('Content', 'Content')
                ->setRows(15)
        ]);

        $fields->removeByName(['Description']);

        $this->extend('updateCMSFields', $fields);

        return $fields;
    }

    public function onBeforeWrite()
    {
        // Set max sortorder
        if (!$this->getField('SortOrder')) {
            $max = ContentTab::get()->filter(['ParentID' => $this->getField('ParentID')])->max('SortOrder');

            $this->setField('SortOrder', (int)$max + 1);
        }

        parent::onBeforeWrite();
    }
    public function getContentSummary()
    {
        return $this->dbObject('Content')->LimitCharacters(250);
    }

    /* ==========================================
     * CRUD
     * ========================================*/

    public function canView($member = null)
    {
        if ($this->Parent()) {
            return $this->Parent()->canView($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null)
    {
        if ($this->Parent()) {
            return $this->Parent()->canEdit($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        if ($this->Parent()) {
            return $this->Parent()->canDelete($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        if ($this->Parent()) {
            return $this->Parent()->canCreate($member, $context);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }
}