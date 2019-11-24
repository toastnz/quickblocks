<?php

namespace Toast\QuickBlocks;

use Axllent\FormFields\FieldType\VideoLink;
use Axllent\FormFields\Forms\VideoLinkField;
use EdgarIndustries\YouTubeField\YouTubeField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;
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
    private static $plural_name   = 'Videos';
    private static $table_name    = 'VideoBlock';
    private static $icon          = 'quickblocks/images/video.svg';

    private static $db = [
        'Caption' => 'Varchar(255)',
        'Video' => VideoLink::class
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
                VideoLinkField::create('Video')
                    ->showPreview(500),
                UploadField::create('Thumbnail', 'Override default Thumbnail')
                           ->setFolderName('Uploads/videos')
                           ->setDescription('Will automatically use YouTube thumbnail if this image is not uploaded. Ideal size: 960x540')
            ]);
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getCMSValidator()
    {
        $required = new RequiredFields(['Title', 'Video']);

        $this->extend('updateCMSValidator', $required);

        return $required;
    }

    public function getContentSummary()
    {
        return DBField::create_field(DBHTMLText::class, $this->Video);
    }


    
}