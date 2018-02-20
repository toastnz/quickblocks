<?php

/**
 * Class SplitBlock
 *
 * @property string LeftContent
 * @property string RightContent
 * @property string LeftHeading
 * @property string RightHeading
 */
class SplitBlock extends QuickBlock
{
    private static $singular_name = 'Split Block';
    private static $plural_name = 'Split Blocks';

    private static $db = [
        'LeftContent'  => 'Text',
        'RightContent' => 'Text',
        'LeftHeading'  => 'Varchar(100)',
        'RightHeading' => 'Varchar(100)'
    ];

    private static $has_one = [
        'LeftImage'  => 'Image',
        'RightImage' => 'Image',
        'LeftLink'   => 'Link',
        'RightLink'  => 'Link'
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
        $fields->addFieldToTab('Root.Main', ToggleCompositeField::create('LeftBlock', 'Left Block', FieldList::create([
            HeaderField::create('', 'Left Block'),
            TextareaField::create('LeftHeading', 'Heading')
                ->setRows(2),
            TextareaField::create('LeftContent', 'Content'),
            LinkField::create('LeftLinkID', 'Link'),
            UploadField::create('LeftImage', 'Image')
                ->setDescription('Ideal size: 400x400'),
        ])));

        // Right Block
        $fields->addFieldToTab('Root.Main', ToggleCompositeField::create('RightBlock', 'Right Block', FieldList::create([
            HeaderField::create('', 'Right Block'),
            TextareaField::create('RightHeading', 'Heading')
                ->setRows(2),
            TextareaField::create('RightContent', 'Content'),
            LinkField::create('RightLinkID', 'Link'),
            UploadField::create('RightImage', 'Image')
                ->setDescription('Ideal size: 400x400'),
        ])));

        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->LeftContent . ' ' . $this->RightContent);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['LeftContent', 'RightContent', 'LeftImage', 'RightImage']);
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