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

class PercentageBlock extends QuickBlock
{
    private static $singular_name = 'Percentage Block';
    private static $plural_name = 'Percentage Blocks';
    private static $table_name = 'PercentageBlock';

    private static $db = [
        'Size' => 'Enum("33/66, 50/50, 66/33", "50/50")',
        'LeftTitle' => 'Text',
        'LeftContent' => 'HTMLText',
        'RightTitle' => 'Text',
        'RightContent' => 'HTMLText'
    ];

    private static $has_one = [
        'LeftImage' => Image::class,
        'RightImage' => Image::class,
        'LeftLink' => Link::class,
        'RightLink' => Link::class
    ];

    private static $owns = [
        'LeftImage',
        'RightImage'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            OptionsetField::create('Size', 'Size', [
                '33/66' => '33/66',
                '50/50' => '50/50',
                '66/33' => '66/33'
            ])
        ]);

        $fields->addFieldsToTab('Root.Left', [
            TextField::create('LeftHeading', 'Heading'),
            HTMLEditorField::create('LeftContent', 'Content'),
            UploadField::create('LeftImage', 'Background Image'),
            LinkField::create('LeftLinkID', 'Link'),
        ]);

        $fields->addFieldsToTab('Root.Right', [
            TextField::create('RightHeading', 'Heading'),
            HTMLEditorField::create('RightContent', 'Content'),
            UploadField::create('RightImage', 'Background Image'),
            LinkField::create('RightLinkID', 'Link'),
        ]);

        return $fields;
    }

    public function getWidth($side)
    {
        switch ($this->Size) {
            case '50/50':
                $left = '6';
                $right = '6';
                break;
            case '33/66':
                $left = '4';
                $right = '8';
                break;
            case '66/33':
                $left = '8';
                $right = '4';
                break;
            default:
                $left = '6';
                $right = '6';
                break;
        }

        if ($side == 'left') {
            return $left;
        } else {
            return $right;
        }
    }

    public function getLeft()
    {
        return new ArrayData([
            'Title' => $this->LeftTitle ? $this->LeftTitle : '',
            'Content' => $this->LeftContent ? DBField::create_field(DBHTMLText::class, $this->LeftContent) : '',
            'Image' => $this->LeftImage ? $this->LeftImage : '',
            'Link' => $this->LeftLink ? $this->LeftLink : '',
            'Size' => $this->getWidth('left'),
        ]);
    }

    public function getRight()
    {
        return new ArrayData([
            'Title' => $this->RightTitle ? $this->RightTitle : '',
            'Content' => $this->RightContent ? DBField::create_field(DBHTMLText::class, $this->RightContent) : '',
            'Image' => $this->RightImage ? $this->RightImage : '',
            'Link' => $this->RightLink ? $this->RightLink : '',
            'Size' => $this->getWidth('right'),
        ]);
    }
}
