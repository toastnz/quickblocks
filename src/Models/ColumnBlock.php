<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use Toast\Model\ColumnBlockItem;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Class ColumnBlock
 *
 */
class ColumnBlock extends QuickBlock
{
    private static $table_name    = 'QuickBlocks_ColumnBlock';

    private static $singular_name = 'Column';
    private static $plural_name   = 'Columns';
    private static $icon          = 'quickblocks/images/text.png';

    private static $db = [
        'Heading' => 'Varchar(255)',
        'ContentLeft' => 'HTMLText',
        'ContentMiddle' => 'HTMLText',
        'ContentRight' => 'HTMLText',
//        'Columns' => 'Enum("1,2,3", "3")'
    ];
//    private static $has_many = [
//        'ColumnItems' => ColumnBlockItem::class
//    ];
    /**
     * @return FieldList
     */

    public function getCMSFields()
    {
//        $config = GridFieldConfig_RelationEditor::create(50)
//            ->removeComponentsByType(GridFieldAddExistingAutoCompleter::class)
//            ->removeComponentsByType(GridFieldDeleteAction::class)
//            ->addComponents(new GridFieldDeleteAction())
//            ->addComponents(GridFieldOrderableRows::create('SortOrder'));
//        $grid = GridField::create(
//            'ColumnItems',
//            'Column Items',
//            $this->ColumnItems(),
//            $config
//        );

        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Heading', 'Heading'),
            HTMLEditorField::create('ContentLeft', 'Content Left')
                ->setRows(15),
            HTMLEditorField::create('ContentMiddle', 'Content Middle')
                ->setRows(15),
            HTMLEditorField::create('ContentRight', 'Content Right')
                ->setRows(15),
//            DropdownField::create('Columns', 'Columns', singleton(ColumnBlock::class)->dbObject('Columns')->enumValues()),
//            $grid
        ]);
        return $fields;
    }

    public function getColumns()
    {
        $columns = 0;
        
        if ($this->ContentLeft !== "") {
            $columns += 1;
        };
        if ($this->ContentMiddle !== "") {
            $columns += 1;
        };
        if ($this->ContentRight !== "") {
            $columns += 1;
        };
        return $columns;
    }

}