<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use Toast\Model\NewsBlockItem;

class NewsBlock extends QuickBlock
{
    private static $singular_name = 'News';
    private static $plural_name   = 'News';
    private static $table_name    = 'NewsBlock';
    private static $icon          = 'quickblocks/images/news.png';

    private static $db = [
        'Content' => 'HTMLText'
    ];

    private static $has_many = [
        'Items' => NewsBlockItem::class
    ];

    public function getCMSFields()
    {
        $NewsConfig = GridFieldConfig_RelationEditor::create(10);
        $NewsConfig->addComponent(GridFieldOrderableRows::create('SortOrder'))
                   ->removeComponentsByType(GridFieldDeleteAction::class)
                   ->addComponent(new GridFieldDeleteAction(false))
                   ->removeComponentsByType('GridFieldAddExistingAutoCompleter');

        $NewsBlockGridField = GridField::create(
            'Items',
            'News Block Items',
            $this->owner->Items(),
            $NewsConfig
        );

        /**
         * @var FieldList $fields
         */
        $fields = parent::getCMSFields();


        /** -----------------------------------------
         * Slide Details
         * ----------------------------------------*/
        if ($this->ID !== 0) {
            $fields->addFieldsToTab('Root.Main', [
                HTMLEditorField::create('Content', 'Content')
            ]);

            $fields->addFieldsToTab('Root.NewsItems', [
                $NewsBlockGridField
            ]);
        }

        return $fields;
    }
    
    public function getLatestNews()
    {

        return BlogPost::get()->Limit(3);
    }
}