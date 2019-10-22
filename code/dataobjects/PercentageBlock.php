<?php
/**
 * Created by PhpStorm.
 * User: hadleelineham
 * Date: 2019-02-28
 * Time: 10:08
 */

namespace Toast\QuickBlocks;


use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;
use SilverStripe\View\ArrayData;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\FieldType\DBField;

class PercentageBlock extends QuickBlock
{
    private static $singular_name = 'Percentage';
    private static $plural_name = 'Percentages';
    private static $table_name = 'PercentageBlock';

    private static $db = [
        'Size' => 'Enum("33/66, 50/50, 66/33", "50/50")',
        'LeftHeading' => 'Text',
        'LeftContent' => 'HTMLText',
        'RightHeading' => 'Text',
        'RightContent' => 'HTMLText',
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

    public function getWidth($side){
        switch ($this->Size){
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


        if ($side == 'left'){
            return $left;
        }else{
            return $right;
        }
    }

    public function getRightBackgroundImageURL(){

        if ($this->RightImage()->exists()){

            return $this->RightImage()->URL;
        }else{
            return 'themes/quicksilver/dist/images/standard/percentage-pattern.png';
        }
    }

    public function getLeftBackgroundImageURL(){
        if ($this->LeftImage()->exists()){
            return $this->LeftImage()->URL;
        }else{
            return 'themes/quicksilver/dist/images/standard/percentage-pattern.png';
        }
    }

    public function getLeft(){
        return new ArrayData    ([
            'Title'   => $this->LeftHeading,
            'Content' => DBField::create_field(DBHTMLText::class, $this->LeftContent),
            'Image'   => $this->LeftImage,
            'Size'   => $this->getWidth('left'),
            'Link'   => $this->LeftLink,
        ]);
    }

    public function getRight(){

        return new ArrayData    ([
            'Title'   => $this->RightHeading,
            'Content' => DBField::create_field(DBHTMLText::class, $this->RightContent),
            'Image'   => $this->RightImage,
            'Size'   => $this->getWidth('right'),
            'Link'   => $this->RightLink,
        ]);
    }    
}