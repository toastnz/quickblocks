<?php


namespace Toast\Model;


use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use Toast\QuickBlocks\ColumnBlock;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class ColumnBlockItem extends DataObject
{
    private static $singular_name = 'ColumnBlock Item';
    private static $plural_name = 'ColumnBlock Items';
    private static $default_sort = 'SortOrder';
    private static $table_name = 'ColumnBlockItem';

    private static $db = [
        'SortOrder' => 'Int',
        'Content' => 'HTMLText',
    ];

    private static $has_one = [
        'ColumnBlock' => ColumnBlock::class
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {

        /**
         * @var FieldList $fields
         */
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'SortOrder',
            'ParentPageID',
            'ColumnBlockID',
            'FileTracking',
            'LinkTracking',
        ]);

        /** =========================================
         * @var FieldList $fields
         * ========================================*/

        $fields->addFieldsToTab('Root.Main', [
            HTMLEditorField::create('Content', 'Content')->setRows(5),
        ]);


        return $fields;
    }

}