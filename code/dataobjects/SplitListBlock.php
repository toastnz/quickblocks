<?php

/**
 * Class SplitListBlock
 */
class SplitListBlock extends QuickBlock
{
    private static $singular_name = 'Split List';
    private static $plural_name = 'Split Lists';

    private static $db = [
        'LeftContent'  => 'HTMLText',
        'RightContent' => 'HTMLText',
        'LeftHeading'  => 'Varchar(100)',
        'RightHeading' => 'Varchar(100)'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Name')
                ->setAttribute('placeholder', 'This is a helper field only (will not show in templates)'),
        ]);

        // Left Block
        $fields->addFieldToTab('Root.Main', ToggleCompositeField::create('LeftList', 'Left List', FieldList::create([
            HeaderField::create('', 'Left List'),
            TextField::create('LeftHeading', 'Heading'),
            HtmlEditorField::create('LeftContent', 'Content')
        ])));

        // Right List
        $fields->addFieldToTab('Root.Main', ToggleCompositeField::create('RightList', 'Right List', FieldList::create([
            HeaderField::create('', 'Right Block'),
            TextField::create('RightHeading', 'Heading'),
            HtmlEditorField::create('RightContent', 'Content')
        ])));

        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->LeftContent . ' ' . $this->RightContent);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['LeftContent', 'RightContent']);
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