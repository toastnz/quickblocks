<?php

/**
 * Class StatisticBlock
 *
 * @method HasManyList|Statistic[] Statistics()
 */
class StatisticBlock extends ContentBlock
{
    private static $singular_name = 'Statistic Block';
    private static $plural_name = 'Statistic Blocks';

    private static $db = [
        'Heading' => 'Varchar(100)'
    ];

    private static $has_many = [
        'Statistics' => 'Statistic'
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

        $grid = GridField::create('Statistics', 'Statistics', $this->Statistics(), $config)
            ->setDescription('Displays in a carousel');

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

    public function getContentSummary()
    {
        if ($this->Statistics() && $this->Statistics()->exists()) {
            return DBField::create_field('HTMLText', implode(', ', $this->Statistics()->column('Title')));
        }
        return DBField::create_field('HTMLText', '');
    }

    /* ==========================================
     * SEARCH
     * ========================================*/

}