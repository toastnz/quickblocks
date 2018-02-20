<?php

/**
 * Class AccordionBlock
 *
 * @method HasManyList|AccordionItem[] AccordionItems()
 */
class AccordionBlock extends ContentBlock
{
    private static $singular_name = 'Accordion';
    private static $plural_name = 'Accordions';

    private static $has_many = [
        'AccordionItems' => 'AccordionItem'
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

        $grid = GridField::create('AccordionItems', 'Accordion Items', $this->AccordionItems(), $config);

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
        if ($this->AccordionItems() && $this->AccordionItems()->exists()) {
            return DBField::create_field('HTMLText', implode(', ', $this->AccordionItems()->column('Heading')));
        }
        return DBField::create_field('HTMLText', '');
    }

    public function getGroupedItems()
    {
        return GroupedList::create($this->AccordionItems());
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