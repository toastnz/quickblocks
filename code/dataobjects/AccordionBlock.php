<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\ORM\FieldType\DBHTMLText;
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
    private static $plural_name   = 'Accordions';
    private static $table_name    = 'AccordionBlock';
    private static $icon          = 'quickblocks/images/accordion.png';

    private static $has_many = [
        'AccordionItems' => AccordionItem::class
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            $config = GridFieldConfig_RelationEditor::create(50)
                                                    ->removeComponentsByType(GridFieldAddExistingAutoCompleter::class)
                                                    ->removeComponentsByType(GridFieldDeleteAction::class)
                                                    ->addComponents(new GridFieldDeleteAction())
                                                    ->addComponents(GridFieldOrderableRows::create('SortOrder'));

            $grid = GridField::create('AccordionItems', 'Accordion Items', $this->AccordionItems(), $config);

            if ($this->ID) {
                $fields->addFieldToTab('Root.Main', $grid);
            } else {
                $fields->addFieldToTab('Root.Main', LiteralField::create('', '<div class="message notice">Save this block to show additional options.</div>'));
            }
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getContentSummary()
    {
        if ($this->AccordionItems() && $this->AccordionItems()->exists()) {
            return DBField::create_field(DBHTMLText::class, implode(', ', $this->AccordionItems()->column('Heading')));
        }
        return DBField::create_field(DBHTMLText::class, '');
    }

    public function getGroupedItems()
    {
        return GroupedList::create($this->AccordionItems());
    }

    public function Items()
    {
        return $this->AccordionItems();
    }
}