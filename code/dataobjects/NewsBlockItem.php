<?php

namespace Toast\Model;

use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use Toast\QuickBlocks\NewsBlock;

class NewsBlockItem extends DataObject
{
    private static $singular_name = 'NewsBlock Item';
    private static $plural_name   = 'NewsBlock Items';
    private static $default_sort  = 'SortOrder';
    private static $table_name = 'NewsBlockItem';

    private static $db         = [
        'SortOrder' => 'Int',
        'Title'     => 'Varchar(100)',
        'Content'   => 'HTMLText',
    ];

    private static $has_one = [
        'Link'      => Link::class,
        'Image'     => Image::class,
        'NewsBlock' => NewsBlock::class
    ];

    private static $summary_fields = [
        'Image.CMSThumbnail' => 'Thumbnail',
        'Title'              => 'Title',
    ];
    private static $owns           = [
        'Image'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {

        /**
         * @var FieldList $fields
         */
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'SortOrder',
            'ParentPageID',
            'NewsBlockID',
            'FileTracking',
            'LinkTracking',
        ]);

        /** =========================================
         * @var FieldList $fields
         * ========================================*/

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            LinkField::create('LinkID', 'Link'),
            HTMLEditorField::create('Content', 'Content')->setRows(5),
            UploadField::create('Image', 'Image')
                       ->setDescription('Ideal size at least 510px * 510px')
                       ->setFolderName('Uploads/Images')
        ]);


        return $fields;
    }

}