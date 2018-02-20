<?php

/**
 * Class Media
 *
 * @property string Heading
 * @property int    SortOrder
 *
 * @method MediaBlock Parent()
 */
class Media extends DataObject
{
    private static $singular_name = 'Item';
    private static $plural_name = 'Items';
    private static $default_sort = 'SortOrder';

    private static $db = [
        'Heading'   => 'Text',
        'SortOrder' => 'Int',
        'VideoID'   => 'Varchar(50)'
    ];

    private static $has_one = [
        'Parent'    => 'MediaBlock',
        'Thumbnail' => 'Image',
        'File'      => 'File',
        'Link'      => 'Link'

    ];

    private static $summary_fields = [
        'Thumbnail.CMSThumbnail' => 'Thumbnail',
        'Heading'                => 'Heading',
        'VideoID'                => 'Video ID',
        'File.Title'             => 'File',
        'Link.LinkURL'           => 'Link'
    ];

    public static $defaults = [
        'State' => 'closed'
    ];

    public function getTitle()
    {
        return $this->Heading;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Heading', 'Heading'),
            YouTubeField::create('VideoID', 'YouTube Video'),
            LinkField::create('LinkID', 'Link'),
            UploadField::create('Thumbnail', 'Thumbnail')
                ->setDescription('Will automatically use YouTube thumbnail if this image is not uploaded. Ideal size: 930x580'),
            UploadField::create('File', 'File')
                ->setAllowedExtensions(['pdf', 'doc', 'docx']),
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->SortOrder) {
            $max = (int)Media::get()->filter(['ParentID' => $this->ParentID])->max('SortOrder');
            $this->setField('SortOrder', $max + 1);
        }
    }

    public function getCMSValidator()
    {
        return new RequiredFields([]);
    }

    public function getFileDownloadLink()
    {
        return Controller::join_links(Controller::curr()->Link(), 'download') . '?files=' . $this->ID;
    }

    /* ==========================================
     * CRUD
     * ========================================*/

    public function canView($member = null)
    {
        if ($this->Parent()) {
            return $this->Parent()->canView($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null)
    {
        if ($this->Parent()) {
            return $this->Parent()->canEdit($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null)
    {
        if ($this->Parent()) {
            return $this->Parent()->canDelete($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null)
    {
        if ($this->Parent()) {
            return $this->Parent()->canCreate($member);
        }
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    /* ==========================================
     * SEARCH
     * ========================================*/


}