<?php

/**
 * Class MediaBlock
 *
 * @method HasManyList|Media[] MediaItems()
 */
class MediaBlock extends ContentBlock
{
    private static $singular_name = 'Media Block';
    private static $plural_name = 'Media Blocks';

    private static $has_many = [
        'MediaItems' => 'Media'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $config = GridFieldConfig_RelationEditor::create(50)
            ->removeComponentsByType('GridFieldAddExistingAutoCompleter')
            ->addComponents(GridFieldOrderableRows::create('SortOrder'));

        $grid = GridField::create('MediaItems', 'Media Items', $this->MediaItems(), $config);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Name')
                ->setAttribute('placeholder', 'This is a helper field only (will not show in templates)')
        ]);

        if ($this->ID) {
            $fields->addFieldToTab('Root.Main', $grid);
        } else {
            $fields->addFieldToTab('Root.Main', LiteralField::create('', '<div class="message notice">Save this block to show additional options.</div>'));
        }

        return $fields;
    }

    public function getCMSValidator()
    {
        return new RequiredFields([]);
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->VideoID);
    }
}