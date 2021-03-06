<?php

namespace Toast\QuickBlocks;

use EdgarIndustries\YouTubeField\YouTubeField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Class VideoBlock
 *
 * @property string Caption
 * @property string VideoID
 */
class VideoBlock extends QuickBlock
{
    private static $singular_name = 'Video';
    private static $plural_name = 'Videos';
    private static $table_name = 'VideoBlock';

    private static $db = [
        'Caption'   => 'Varchar(255)',
        'VideoID'   => 'Varchar(50)'
    ];

    private static $has_one = [
        'Thumbnail' => Image::class
    ];

    private static $owns = [
        'Thumbnail'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $fields->addFieldsToTab('Root.Main', [
                TextField::create('Caption', 'Caption'),
                YouTubeField::create('VideoID', 'YouTube Video'),
                UploadField::create('Thumbnail', 'Thumbnail')
                    ->setFolderName('Uploads/videos')
                    ->setDescription('Will automatically use YouTube thumbnail if this image is not uploaded. Ideal size: 960x540')
            ]);
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getCMSValidator()
    {
        $required = new RequiredFields(['Title', 'VideoID']);

        $this->extend('updateCMSValidator',$required);

        return $required;
    }

    public function getContentSummary()
    {
        return DBField::create_field(DBHTMLText::class, $this->VideoID);
    }
}