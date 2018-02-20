<?php

/**
 * Class AccordionItem
 *
 * @property string Heading
 * @property string Content
 * @property int    SortOrder
 *
 * @method AccordionBlock Parent()
 */
class AccordionItem extends DataObject
{
    private static $singular_name = 'Item';
    private static $plural_name = 'Items';
    private static $default_sort = 'SortOrder';

    private static $db = [
        'Heading'   => 'Text',
        'Content'   => 'HTMLText',
        'SortOrder' => 'Int',
    ];

    private static $has_one = [
        'Parent' => 'AccordionBlock'
    ];

    private static $summary_fields = [
        'Heading'         => 'Heading',
        'Content.Summary' => 'Content'
    ];


    public function getTitle()
    {
        return $this->Heading;
    }

    public function DisplayTitle()
    {
        $title = $this->Heading;

        $title = strlen($title) > 20 ? '<span>' . $title . '</span>' : $title;

        return DBField::create_field('HTMLText', $title);
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Heading', 'Heading'),
            HtmlEditorField::create('Content', 'Content'),
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->SortOrder) {
            $max = (int)AccordionItem::get()->filter(['ParentID' => $this->ParentID])->max('SortOrder');
            $this->setField('SortOrder', $max + 1);
        }
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Heading', 'Content']);
    }

    public function GroupNumber()
    {
        return 'group' . ceil($this->SortOrder / 2);
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

    /**
     * Filter array
     * eg. array('Disabled' => 0);
     * @return array
     */
    public static function getSearchFilter()
    {
        return [];
    }

    /**
     * Fields that compose the Title
     * eg. array('Title', 'Subtitle');
     * @return array
     */
    public function getTitleFields()
    {
        return ['Heading'];
    }

    /**
     * Fields that compose the Content
     * eg. array('Teaser', 'Content');
     * @return array
     */
    public function getContentFields()
    {
        return ['Content'];
    }

    /**
     * Parent objects that should be displayed in search results.
     * @return SiteTree or SearchableLinkable
     */
    public function getOwner()
    {
        return $this->Parent()->Parent();
    }

    /**
     * Whatever this specific Searchable should be included in search results.
     * This allows you to exclude some DataObjects from search results.
     * It plays more or less the same role that ShowInSearch plays for SiteTree.
     * @return boolean
     */
    public function IncludeInSearch()
    {
        return true;
    }
}