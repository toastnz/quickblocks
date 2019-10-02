<?php

namespace Toast\QuickBlocks;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Security\Permission;
use SilverStripe\ORM\DataObject;

/**
 * Class GalleryImageItem
 *
 * @property string Title
 * @property int SortOrder
 *
 * @method GalleryBlock GalleryBlock()
 */
class GalleryImageItem extends DataObject
{
    private static $singular_name = 'Gallery Image Item';
    private static $plural_name   = 'Gallery Image Items';
    private static $default_sort  = 'SortOrder';
    private static $table_name    = 'GalleryImageItem';

    private static $db = [
        'Title'     => 'Text',
        'SortOrder' => 'Int',
    ];

    private static $has_one = [
        'GalleryBlock' => GalleryBlock::class,
        'GalleryImage' => Image::class
    ];

    private static $owns = [
        'GalleryImage'
    ];

    private static $summary_fields = [
        'Title'                     => 'Title',
        'GalleryImage.CMSThumbnail' => 'Image'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            UploadField::create('GalleryImage', 'Image')->setFolderName('Uploads/blocks/gallery-images')
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
    }

    public function getCMSValidator()
    {
        return new RequiredFields([Image::class]);
    }

    /* ==========================================
     * CRUD
     * ========================================*/

    public function canView($member = null)
    {
        if ($this->GalleryBlock()) {
            return $this->GalleryBlock()->canView($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null)
    {
        if ($this->GalleryBlock()) {
            return $this->GalleryBlock()->canEdit($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        if ($this->GalleryBlock()) {
            return $this->GalleryBlock()->canDelete($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        if ($this->GalleryBlock()) {
            return $this->GalleryBlock()->canCreate($member, $context);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }
}
