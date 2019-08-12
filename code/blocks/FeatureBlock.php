<?php

namespace Toast\QuickBlocks;


use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;
use SilverStripe\View\SSViewer;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\FieldType\DBField;

class FeatureBlock extends QuickBlock
{
    private static $singular_name = 'Feature Block';
    private static $plural_name = 'Feature Blocks';
    private static $table_name = 'FeatureBlock';

    private static $db = [
        'Heading' => 'Text',
        'Content' => 'HTMLText',
        'ImagePostition' => 'Enum("Left, Right", "Right")',
    ];

    private static $has_one = [
        'Image' => Image::class,
        'Link' => Link::class
    ];

    private static $owns = [
        'Image'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Heading', 'Heading'),
            HTMLEditorField::create('Content', 'Content'),
            UploadField::create('Image', 'Image'),
            LinkField::create('LinkID', 'Link'),
            OptionsetField::create('ImagePostition', 'Image Postition', [
                'Left' => 'Left',
                'Right' => 'Right'
            ])
        ]);

        return $fields;
    }

}