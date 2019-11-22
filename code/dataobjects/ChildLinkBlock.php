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
use Page;

class ChildLinkBlock extends QuickBlock
{
    private static $singular_name = 'Children';
    private static $plural_name   = 'Children Links';
    private static $table_name    = 'ChildLinkBlock';
    private static $icon          = 'quickblocks/images/link.png';

    private static $db = [
        'Title'   => 'Varchar(100)',
        'Content' => 'HTMLText',
        'Columns' => 'Enum("2, 3, 4", "2")',
    ];


    public function getCMSFields()
    {
        /**
         * @var FieldList $fields
         */
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Title'),
            HTMLEditorField::create('Content', 'Content')
                           ->setRows(15),
            DropdownField::create('Columns', 'Columns', singleton('Toast\QuickBlocks\ChildLinkBlock')->dbObject('Columns')->enumValues()),
        ]);

        return $fields;
    }

    public function getItems(){
        $page = Page::get()->filter(array('ContentBlocks.ID' => $this->ID))->first();

        return $page->Children();
    }

    function forTemplate()
    {

        return $this->renderWith('Toast\QuickBlocks\LinkBlock');
    }
}