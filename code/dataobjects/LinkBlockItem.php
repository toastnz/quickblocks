<?php
/**
 * Created by PhpStorm.
 * User: staff
 * Date: 1/02/19
 * Time: 3:43 PM
 */

namespace Toast\Model;


use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use StevieMayhew\SilverStripeSVG\SVGTemplate;
use Toast\Forms\IconOptionsetField;
use Toast\QuickBlocks\LinkBlock;

class LinkBlockItem extends  DataObject
{
    private static $table_name = 'LinkBlockItem';
    private static $db = [
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
    private static $owns = [
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
            LinkField::create('LinkID','Link'),
            UploadField::create('Image', 'Image')
                ->setDescription('Ideal size at least 510px * 510px')
                ->setFolderName('Uploads/Images')
        ]);


        return $fields;
    }

}