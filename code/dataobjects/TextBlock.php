<?php

/**
 * Class TextBlock
 *
 * @property string Content
 */
class TextBlock extends QuickBlock
{
    private static $singular_name = 'Text';
    private static $plural_name = 'Text';

    private static $db = [
        'Content' => 'HTMLText'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Name')
                ->setAttribute('placeholder', 'This is a helper field only (will not show in templates)'),
            HtmlEditorField::create('Content', 'Content')
        ]);

        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->Content);
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