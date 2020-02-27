<?php

namespace Toast\Model;

use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use Toast\Forms\IconOptionsetField;
use Toast\QuickBlocks\LinkBlock;

class LinkBlockItem extends DataObject
{
    private static $table_name = 'QuickBlocks_LinkBlockItem';

    private static $db         = [
        'SortOrder' => 'Int',
        'Title'     => 'Varchar(100)',
    ];

    private static $has_one = [
        'Link'      => Link::class,
        'Image'     => Image::class,
        'LinkBlock' => LinkBlock::class
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
            'LinkBlockID',
            'FileTracking',
            'LinkTracking',
        ]);

        /** =========================================
         * @var FieldList $fields
         * ========================================*/

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            LinkField::create('LinkID', 'Link'),
            UploadField::create('Image', 'Image')
                       ->setDescription('Ideal size at least 510px * 510px')
                       ->setFolderName('Uploads/Images')
        ]);


        return $fields;
    }

}