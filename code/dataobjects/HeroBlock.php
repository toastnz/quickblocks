<?php

namespace Toast\QuickBlocks;

use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Toast\Model\BannerSliderImage;
use Toast\Model\FeatureBlockImage;
use SilverStripe\Assets\Image;

class HeroBlock extends QuickBlock
{
    private static $singular_name = 'Hero block';
    private static $plural_name   = 'Hero blocks';
    private static $table_name    = 'HeroBlock';
    private static $icon          = 'quickblocks/images/hero.png';

    private static $db = [
        'Title'        => 'Text',
        'Content'      => 'HTMLText',
        'ContentWidth' => 'Boolean'
    ];

    private static $has_one = [
        'BackgroundImage' => Image::class
    ];

    private static $owns = [
        'BackgroundImage'
    ];

    public function getCMSFields()
    {
        /** =========================================
         * @var FieldList $fields
         * ========================================*/
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            HTMLEditorField::create('Content', 'Content')->setRows(5),
            DropdownField::create('ContentWidth', 'Width', [0 => 'Full Width', 1 => 'Content Width']),
            UploadField::create('BackgroundImage', 'Background Image')
        ]);

        return $fields;
    }

//    public function SlideImagesPublished() {
//        $published = [];
//        foreach ($this->owner->SlideImages() as $image) {
//            if ($image->owner->SlideImage()->ID) {
//                $published[] = $image->owner->SlideImage()->ID;
//            }
//        }
//
//        return (count($published) > 1) ? true : false;
//    }

}
