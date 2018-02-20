<?php

/**
 * Class Statistic
 *
 * @property string Title
 * @property int    SortOrder
 *
 * @method StatisticBlock Parent()
 */
class Statistic extends DataObject
{
    private static $singular_name = 'Statistic';
    private static $plural_name = 'Statistics';
    private static $default_sort = 'SortOrder';

    private static $db = [
        'Title'     => 'Text',
        'SortOrder' => 'Int'
    ];

    private static $has_one = [
        'Parent' => 'StatisticBlock'
    ];

    private static $many_many = [
        'Graphs' => 'Image'
    ];

    private static $summary_fields = [
        'Graphs.First.CMSThumbnail' => 'Graph',
        'Title'                     => 'Title'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            UploadField::create('Graphs', 'Graph')
                ->setDescription('Ideal size: 845x470')
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->SortOrder) {
            $max = (int)Statistic::get()->filter(['ParentID' => $this->ParentID])->max('SortOrder');
            $this->setField('SortOrder', $max + 1);
        }
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Title']);
    }

    public function forTemplate()
    {
        return $this->renderWith('StatisticModal')->forTemplate();
    }

    /**
     * To display in modal
     *
     * @return String
     */
    public function getApiURL()
    {
        return Controller::join_links(Controller::curr()->AbsoluteLink(), 'statistic', $this->ID);
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