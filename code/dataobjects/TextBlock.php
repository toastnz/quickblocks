<?php

namespace Toast;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;

/**
 * Class TextBlock
 *
 * @property string Content
 */
class TextBlock extends QuickBlock
{
    private static $singular_name = 'Text';
    private static $plural_name = 'Text';
    private static $table_name = 'TextBlock';

    private static $db = [
        'Content' => 'HTMLText'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', HtmlEditorField::create('Content', 'Content'));

        return $fields;
    }

    public function getContentSummary()
    {
        return $this->dbObject('Content')->LimitCharacters(250);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Content']);
    }

    /* ==========================================
     * SEARCH
     * ========================================*/


    public function getShowInSearch() {
        return 1;
    }

    public function getAbstract()
    {
        return $this->getContentSummary()->forTemplate();
    }

}