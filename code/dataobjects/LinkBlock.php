<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Toast\Model\LinkBlockItem;

class LinkBlock extends QuickBlock
{
    private static $singular_name = 'Link Block';
    private static $plural_name   = 'Link Blocks';
    private static $table_name    = 'LinkBlock';
    private static $icon          = 'quickblocks/images/link.png';

    private static $db = [
        'Title'   => 'Varchar(100)',
        'Content' => 'HTMLText',
        'Columns' => 'Enum("2, 3, 4", "2")',
    ];

    private static $has_many = [
        'Items' => LinkBlockItem::class
    ];


    public function getCMSFields()
    {
        $LinkConfig = GridFieldConfig_RelationEditor::create(10);
        $LinkConfig->addComponent(GridFieldOrderableRows::create('SortOrder'))
                   ->removeComponentsByType(GridFieldDeleteAction::class)
                   ->addComponent(new GridFieldDeleteAction(false))
                   ->removeComponentsByType('GridFieldAddExistingAutoCompleter');

        $LinkBlockGridField = GridField::create(
            'Items',
            'Link Block Items',
            $this->owner->Items(),
            $LinkConfig
        );

        /**
         * @var FieldList $fields
         */
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            HTMLEditorField::create('Content', 'Content')
                           ->setRows(15),
            DropdownField::create('Columns', 'Columns', singleton('Toast\QuickBlocks\LinkBlock')->dbObject('Columns')->enumValues()),
        ]);

        /** -----------------------------------------
         * Slide Details
         * ----------------------------------------*/
        if ($this->ID !== 0) {
            $fields->addFieldsToTab('Root.Main', [
                $LinkBlockGridField
            ]);
        }

        return $fields;
    }
}