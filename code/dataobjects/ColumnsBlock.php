<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;

/**
 * Class ColumnsBlock
 *
 */
class ColumnBlock extends QuickBlock
{
    private static $singular_name = 'Column';
    private static $plural_name   = 'Columns';
    private static $table_name    = 'ColumnBlock';
    private static $icon          = 'quickblocks/images/text.png';

    private static $db = [
        'Heading' => 'Varchar(255)',
        'Content' => 'HTMLText',
        'Columns' => 'Enum("1,2,3,4,5,6,7,8,9,10", "3")'
    ];
    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $this->beforeUpdateCMSFields(function ($fields) {

            $fields->addFieldsToTab('Root.Main', [
                TextField::create('Heading', 'Heading'),
                HTMLEditorField::create('Content', 'Content')
                    ->setRows(15),
                DropdownField::create('Columns', 'Columns', singleton(ColumnBlock::class)->dbObject('Columns')->enumValues()),
            ]);
        });

        return $fields;
    }

}