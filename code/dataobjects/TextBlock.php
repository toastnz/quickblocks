<?php

namespace Toast\QuickBlocks;

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
    private static $plural_name   = 'Text';
    private static $table_name    = 'TextBlock';
    private static $icon          = 'quickblocks/images/text.png';

    private static $db = [
        'Content' => 'HTMLText'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $fields->addFieldToTab('Root.Main', HtmlEditorField::create('Content', 'Content'));
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getContentSummary()
    {
        return $this->dbObject('Content')->LimitCharacters(250);
    }

    public function getCMSValidator()
    {
        $required = new RequiredFields(['Content']);

        $this->extend('updateCMSValidator', $required);

        return $required;
    }
}