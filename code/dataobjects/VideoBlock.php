<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Class VideoBlock
 *
 * @property string Caption
 * @property string Video
 * @property string thumbQualityCms
 * @property string thumbQualityFront
 */
class VideoBlock extends QuickBlock
{
    private static $singular_name = 'Video';
    private static $plural_name   = 'Videos';
    private static $table_name    = 'VideoBlock';

    private static $db = [
        'Caption'           => 'Varchar(255)',
        'Video'             => 'Text',
        'thumbQualityCms'   => 'Varchar(20)',
        'thumbQualityFront' => 'Varchar(20)'
    ];

    private static $has_one = [];

    private static $owns = [];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {

            $fields->addFieldsToTab('Root.Main', [
                TextField::create('Video', 'Video URL')
                         ->setDescription('NOTE: Only accepts either YouTube or Vimeo. <br>Paste the whole URL link for the video.'),
                TextField::create('Caption', 'Caption')
            ]);

            if ($this->Video && $this->checkVideoSource()) {

                if ($this->IsVimeo()) {
                    $cmsQualityList = $this->VimeoThumbQuality();
                    $frontendQualityList = $this->VimeoThumbQuality();
                    $title = 'Vimeo Thumbnail';
                } else {
                    $cmsQualityList = $this->YouTubeThumbQuality();
                    $frontendQualityList = $this->YouTubeThumbQuality();
                    $title = 'You Tube Thumbnail';
                }

                $html = '<div class="form-group field text"><label class="form__field-label">' . $title . '</label><div class="form__field-holder"><img src="' . $this->VideoThumbnail($this->thumbQualityCms) . '"></div></div>';

                $fields->addFieldsToTab('Root.Main', [
                    DropdownField::create('thumbQualityCms', 'Thumbnail Size CMS', $cmsQualityList),
                    DropdownField::create('thumbQualityFront', 'Thumbnail Size Front-End', $frontendQualityList),
                    LiteralField::create('VideoThumbnail', $html)
                ]);
            }

        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function validate()
    {
        $result = parent::validate();

        if (!$this->checkVideoSource()) {
            $result->addError('Video URL must be from either YouTube or Vimeo.');
        }

        $this->extend('updateValidate', $result);

        return $result;
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

    /**
     * Check for a valid video source
     *
     * @return bool
     */
    public function checkVideoSource()
    {
        $sourceList = [
            'youtube',
            'youtu.be',
            'vimeo'
        ];

        foreach ($sourceList as $url) {
            if (stripos($this->Video, $url) !== false) {
                return true;
            }
        }

        return false;

    }

    /**
     * Check for YouTube clip
     *
     * @return bool
     */
    public function IsYouTube()
    {
        $sourceList = [
            'youtube',
            'youtu.be'
        ];

        foreach ($sourceList as $url) {
            if (stripos($this->Video, $url) !== false) {
                return true;
            }
        }

        return false;

    }

    /**
     * Check for Vimeo clip
     *
     * @return bool
     */
    public function IsVimeo()
    {
        if (stripos($this->Video, 'vimeo') !== false) {
            return true;
        }

        return false;

    }

    /**
     * Get list of YouTube thumbnail quality levels
     *
     * @return array
     */
    public function YouTubeThumbQuality()
    {
        // Reference: https://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api

        $list = [
            '2'             => 'Thumbnail',
            'mqdefault'     => 'Small',
            'hqdefault'     => 'Medium',
            'sddefault'     => 'Large',
            'maxresdefault' => 'HD'
        ];

        return $list;
    }

    /**
     * Get list of Vimeo thumbnail quality levels
     *
     * @return array
     */
    public function VimeoThumbQuality()
    {
        $list = [
            'medium' => 'Small',
            'large'  => 'Large'
        ];

        return $list;
    }

    /**
     * Get video thumbnail
     *
     * @param string $quality
     * @return string
     */
    public function VideoThumbnail($quality = false)
    {
        if ($this->IsVimeo()) {
            $quality = !empty(array_keys($this->VimeoThumbQuality(), $quality)) ?: 'medium';
            $imageUrl = $this->VimeoThumbnail($quality);
        } else {
            $quality = !empty(array_keys($this->YouTubeThumbQuality(), $quality)) ?: 'mqdefault';
            $imageUrl = $this->YouTubeThumbnail($quality);
        }

        return $imageUrl;
    }

    /**
     * Get YouTube video ID
     *
     * @param bool $url
     * @return bool|mixed
     */
    public function getYouTubeId($url = false)
    {
        if ($url) {
            parse_str(parse_url($url, PHP_URL_QUERY), $urlVars);
            return $urlVars['v'];
        }

        return false;
    }

    /**
     * Get YouTube thumbnail
     *
     * @param $quality
     * @return bool|string
     */
    public function YouTubeThumbnail($quality)
    {
        $id = ($this->getYouTubeId($this->owner->Video)) ?: false;

        if ($id) {
            $imageUrl = 'https://img.youtube.com/vi/' . $id . '/' . $quality . '.jpg';
            return $imageUrl;
        }

        return false;
    }

    /**
     * Get Vimeo thumbnail
     *
     * @param $quality
     * @return string
     */
    public function VimeoThumbnail($quality)
    {
        $id = trim(str_replace(['https://vimeo.com/', 'https://player.vimeo.com/video/'], '', $this->Video));

        if ($id == '') {
            return false;
        }

        $imageUrl = false;

        $apiData = unserialize(file_get_contents("https://vimeo.com/api/v2/video/" . $id . ".php"));

        if (is_array($apiData) && count($apiData) > 0) {

            $videoInfo = $apiData[0];

            $imageUrl = $videoInfo['thumbnail_' . $quality];

        }

        return $imageUrl;

    }
}
