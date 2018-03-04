<?php

use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\GroupedList;

/**
 * Class AccordionBlock
 *
 * @method HasManyList|AccordionItem[] AccordionItems()
 */
class AccordionBlock extends QuickBlock
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
        $fields = parent::getCMSFields();

        $config = GridFieldConfig_RelationEditor::create(50)
            ->removeComponentsByType('GridFieldAddExistingAutoCompleter')
            ->removeComponentsByType(GridFieldDeleteAction::class)
            ->addComponents(new GridFieldDeleteAction())
            ->addComponents(GridFieldOrderableRows::create('SortOrder'));

        $grid = GridField::create('AccordionItems', 'Accordion Items', $this->AccordionItems(), $config);

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