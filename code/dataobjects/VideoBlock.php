<?php

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

    private static $db = [
        'Caption'   => 'Varchar(255)',
        'VideoID'   => 'Varchar(50)'
    ];

    private static $has_one = [
        'Thumbnail' => 'Image'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Caption', 'Caption'),
            YouTubeField::create('VideoID', 'YouTube Video'),
            UploadField::create('Thumbnail', 'Thumbnail')
                ->setFolderName('Uploads/videos')
                ->setDescription('Will automatically use YouTube thumbnail if this image is not uploaded. Ideal size: 960x540')
        ]);

        return $fields;
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Title', 'VideoID']);
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->VideoID);
    }
}