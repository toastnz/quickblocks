<?php
/**
 * Created by PhpStorm.
 * User: hadleelineham
 * Date: 2019-02-28
 * Time: 10:08
 */

namespace Toast\QuickBlocks;


use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\GroupedList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class LinksBlock extends QuickBlock
{
    private static $singular_name = 'Links Block';
    private static $plural_name = 'Links Blocks';
    private static $table_name = 'LinksBlock';

    private static $db = [
        'Heading' => 'Text'
    ];

    private static $has_one = [
        'ParentPage' => SiteTree::class,
    ];
    
    private static $has_many = [
        'Links' => LinksBlockItem::class,
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

            $grid = GridField::create('Links', 'Links', $this->Links(), $config);

            if ($this->ID) {
                $fields->addFieldToTab('Root.Main', $grid);
                $fields->addFieldToTab('Root.Main', TreeDropdownField::create('ParentPageID', 'Parent Page', SiteTree::class ));
                
            } else {
                $fields->addFieldToTab('Root.Main', LiteralField::create('', '<div class="message notice">Save this block to show additional options.</div>'));
            }
        });

        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', TextField::create('Heading', 'Heading'));
        return $fields;
    }

    public function getContentSummary()
    {
        if ($this->Links() && $this->Links()->exists()) {
            return DBField::create_field(DBHTMLText::class, implode(', ', $this->Links()->column('Title')));
        }
        return DBField::create_field(DBHTMLText::class, '');
    }

    public function getGroupedItems()
    {
        return GroupedList::create($this->Links());
    }
}