<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\TabSet;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use Toast\QuickBlocks\SliderBlock;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\Assets\Image;

/**
 * Class SliderItem
 *
 * @property string Heading
 * @property string Content
 * @property int    SortOrder
 *
 * @method SliderBlock Parent()
 */
class SliderItem extends DataObject
{
    private static $singular_name = 'Slider Item';
    private static $plural_name = 'Slider Items';
    private static $default_sort = 'SortOrder';
    private static $table_name = 'SliderItem';

    private static $db = [
        'Heading'   => 'Text',
        'Content'   => 'HTMLText',
        'SortOrder' => 'Int',
    ];

    private static $has_one = [
        'Parent' => SliderBlock::class,
        'Link' => Link::class,
        'Image' => Image::class
    ];

    private static $owns = [
        'Image'
    ];

    private static $summary_fields = [
        'Heading'         => 'Heading',
        'Content.Summary' => 'Content'
    ];


    public function getTitle()
    {
        return $this->Heading;
    }

    public function DisplayTitle()
    {
        $title = $this->Heading;

        $title = strlen($title) > 20 ? '<span>' . $title . '</span>' : $title;

        return DBField::create_field(DBHTMLText::class, $title);
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Heading', 'Heading'),
            HtmlEditorField::create('Content', 'Content'),
            UploadField::create('Image', 'Image'),
            LinkField::create('LinkID', 'Link'),
        ]);

        $this->extend('updateCMSFields', $fields);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->SortOrder) {
            $max = (int)SliderItem::get()->filter(['ParentID' => $this->ParentID])->max('SortOrder');
            $this->setField('SortOrder', $max + 1);
        }
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Heading', 'Content']);
    }

    public function GroupNumber()
    {
        return 'group' . ceil($this->SortOrder / 2);
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