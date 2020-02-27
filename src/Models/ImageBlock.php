<?php

namespace Toast\QuickBlocks;

use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Forms\DropdownField;

/**
 * Class ImageBlock
 *
 * @property string Size
 *
 * @method Image Image
 */
class ImageBlock extends QuickBlock
{
    private static $table_name    = 'QuickBlocks_ImageBlock';

    private static $singular_name = 'Image';
    private static $plural_name   = 'Images';
    private static $icon          = 'quickblocks/images/image.png';

    private static $db = [
        'ContentWidth' => 'Boolean'
    ];

    private static $has_one = [
        'Image' => Image::class,
    ];

    private static $owns = [
        'Image'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $fields->addFieldsToTab('Root.Main', [
                UploadField::create('Image', 'Image')
                           ->setFolderName('Uploads/page-images'),
                DropdownField::create('ContentWidth', 'Width', [0 => 'Full Width', 1 => 'Content Width']),
            ]);
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getContentSummary()
    {
        $content = '';

        if ($this->Image() && $this->Image()->exists()) {
            $content = $this->Image()->Fit(300, 150)->forTemplate();
        }
        return DBField::create_field(DBHTMLText::class, $content);
    }

    public function getCMSValidator()
    {
        return new RequiredFields([Image::class]);
    }
}