<?php

namespace Toast\QuickBlocks;

use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Class ImageBlock
 *
 * @property string Size
 *
 * @method Image Image
 */
class ImageBlock extends QuickBlock
{
    private static $singular_name = 'Image';
    private static $plural_name = 'Images';
    private static $table_name = 'ImageBlock';

    private static $has_one = [
        'Image' => Image::class
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            UploadField::create('Image', 'Image')
                ->setFolderName('Uploads/page-images')
        ]);

        $this->extend('updateCMSFields', $fields);

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