<?php

namespace Toast\QuickBlocks;

use Sheadawson\Linkable\Forms\LinkField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Class SplitBlock
 *
 * @property string LeftContent
 * @property string RightContent
 * @property string LeftHeading
 * @property string RightHeading
 */
class SplitBlock extends QuickBlock
{
    private static $singular_name = 'Split Block';
    private static $plural_name = 'Split Blocks';
    private static $table_name = 'SplitBlock';

    private static $db = [
        'LeftContent'  => 'Text',
        'RightContent' => 'Text',
        'LeftHeading'  => 'Varchar(100)',
        'RightHeading' => 'Varchar(100)'
    ];

    private static $has_one = [
        'LeftImage'  => Image::class,
        'RightImage' => Image::class,
        'LeftLink'   => 'Link',
        'RightLink'  => 'Link'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        // Left Block
        $fields->addFieldToTab('Root.Main', ToggleCompositeField::create('LeftBlock', 'Left Block', FieldList::create([
            HeaderField::create('', 'Left Block'),
            TextareaField::create('LeftHeading', 'Heading')
                ->setRows(2),
            TextareaField::create('LeftContent', 'Content'),
            LinkField::create('LeftLinkID', 'Link'),
            UploadField::create('LeftImage', 'Image')
                ->setDescription('Ideal size: 400x400'),
        ])));

        // Right Block
        $fields->addFieldToTab('Root.Main', ToggleCompositeField::create('RightBlock', 'Right Block', FieldList::create([
            HeaderField::create('', 'Right Block'),
            TextareaField::create('RightHeading', 'Heading')
                ->setRows(2),
            TextareaField::create('RightContent', 'Content'),
            LinkField::create('RightLinkID', 'Link'),
            UploadField::create('RightImage', 'Image')
                ->setDescription('Ideal size: 400x400'),
        ])));

        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field(DBHTMLText::class, $this->LeftContent . ' ' . $this->RightContent);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['LeftContent', 'RightContent', 'LeftImage', 'RightImage']);
    }
}