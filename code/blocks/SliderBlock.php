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
 * Class SliderBlocks
 *
 * @method HasManyList|AccordionItem[] AccordionItems()
 */
class SliderBlock extends QuickBlock
{
    private static $singular_name = 'Slider Block';
    private static $plural_name = 'Slider Blocks';
    private static $table_name = 'SliderBlock';

    private static $has_many = [
        'SliderItems' => SliderItem::class
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

            $grid = GridField::create('SliderItems', 'Slider Items', $this->SliderItems(), $config);

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
        if ($this->SliderItems() && $this->SliderItems()->exists()) {
            return DBField::create_field(DBHTMLText::class, implode(', ', $this->SliderItems()->column('Heading')));
        }
        return DBField::create_field(DBHTMLText::class, '');
    }

    public function getGroupedItems()
    {
        return GroupedList::create($this->SliderItems());
    }
}