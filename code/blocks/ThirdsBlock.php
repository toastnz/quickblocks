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

class ThirdsBlock extends QuickBlock
{
    private static $singular_name = 'Thirds Block';
    private static $plural_name = 'Thirds Blocks';
    private static $table_name = 'ThirdsBlock';

    private static $db = [
        'LeftContent' => 'HTMLText',
        'MiddleContent' => 'HTMLText',
        'RightContent' => 'HTMLText',
    ];
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            HTMLEditorField::create('LeftContent', 'Left Content'),
            HTMLEditorField::create('MiddleContent', 'Middle Content'),
            HTMLEditorField::create('RightContent', 'Right Content')
        ]);

        return $fields;
    }
}
