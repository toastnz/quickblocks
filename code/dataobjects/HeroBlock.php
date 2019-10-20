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

class HeroBlock extends QuickBlock
{
    private static $singular_name = 'Hero block';
    private static $plural_name   = 'Hero blocks';
    private static $table_name    = 'HeroBlock';
    private static $icon          = 'quickblocks/images/image.png';

    private static $db = [
        'Title'          => 'Text'
    ];

    private static $has_many = [
        'SlideImages' => HeroSliderImage::class
    ];

    private static $owns = [];

    public function getCMSFields()
    {
        $noteStyles = 'background-color:#dfecf8;color:#2e668e;padding:10px;margin:0 0 20px 0;';

        /** =========================================
         * @var FieldList $fields
         * ========================================*/
        $fields = parent::getCMSFields();

        $fields->removeByName('BlockLink');

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            HTMLEditorField::create('Content', 'Content')
                           ->setRows(15),

        ]);

        // Images
        $config = GridFieldConfig_RelationEditor::create(10);
        $config->addComponent(GridFieldOrderableRows::create('SortOrder'))
               ->removeComponentsByType(GridFieldDeleteAction::class)
               ->addComponent(new GridFieldDeleteAction(false));
        $imagesGridField = GridField::create(
            'SlideImages',
            'Slide Images',
            $this->owner->SlideImages(),
            $config
        );

        $fields->addFieldsToTab('Root.Images', [
            $imagesGridField
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
