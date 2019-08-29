<?php

namespace Toast\QuickBlocks;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\LiteralField;

/**
 * Class VideoBlock
 *
 * @property string Caption
 * @property string Video
 * @property string thumbQualityCms
 * @property string thumbQualityFront
 */
class VideoBlockExtension extends QuickBlock
{
    private static $singular_name = 'Video';
    private static $plural_name   = 'Videos';
    private static $table_name    = 'VideoBlock';

    private static $db = [
        'Caption'   => 'Varchar(255)',
        'Video'             => 'Text',
        'thumbQualityCms'   => 'Varchar(20)',
        'thumbQualityFront' => 'Varchar(20)'
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
                TextField::create('Video', 'Video URL')
                         ->setDescription('NOTE: Only accepts either YouTube or Vimeo. <br>Paste the whole URL link for the video.'),
                TextField::create('Caption', 'Caption')
            ]);

            if ($this->IsVimeo()) {
                $cmsQualityList = $this->VimeoThumbQuality();
                $frontendQualityList = $this->VimeoThumbQuality();
                $title = 'Vimeo Thumbnail';
            } else {
                $cmsQualityList = $this->YouTubeThumbQuality();
                $frontendQualityList = $this->YouTubeThumbQuality();
                $title = 'You Tube Thumbnail';
            }

            $fields->addFieldsToTab('Root.Main', [
                DropdownField::create('thumbQualityCms', 'Thumbnail Size CMS', $cmsQualityList),
                DropdownField::create('thumbQualityFront', 'Thumbnail Size Front-End', $frontendQualityList)
            ]);

            if ($this->Video) {
                $html = '<div class="form-group field text"><label class="form__field-label">' . $title . '</label><div class="form__field-holder"><img src="' . $this->VideoThumbnail($this->thumbQualityCms) . '"></div></div>';
                $fields->addFieldToTab('Root.Main', LiteralField::create('VideoThumbnail', $html));
            }

            $fields->addFieldsToTab('Root.Main', [
                DropdownField::create('BackgroundColor', 'Background Color', $this->dbObject('BackgroundColor')->enumValues()),
                DropdownField::create('BackgroundShape', 'Background Shape', $this->dbObject('BackgroundShape')->enumValues()),
                DropdownField::create('BackgroundAlignment', 'Background Alignment', $this->dbObject('BackgroundAlignment')->enumValues())
            ]);
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getCMSValidator()
    {
        $required = new RequiredFields(['Title', 'Video']);

        $this->extend('updateCMSValidator',$required);

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
    public function VideoThumbnail($quality = '1')
    {
        if ($this->IsVimeo()) {
            $quality = ($quality) ?: 'medium';
            $imageUrl = $this->VimeoThumbnail($quality);
        } else {
            $quality = ($quality) ?: '1';
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
        $id = str_replace(['https://youtu.be/', 'https://www.youtube.com/watch?v='], '', $this->Video);
        $imageUrl = 'https://img.youtube.com/vi/' . $id . '/' . $quality . '.jpg';

        return $imageUrl;
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