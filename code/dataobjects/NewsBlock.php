<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use Toast\Model\NewsBlockItem;
use SilverStripe\Blog\Model\BlogPost;

class NewsBlock extends QuickBlock
{
    private static $singular_name = 'News';
    private static $plural_name   = 'News';
    private static $table_name    = 'NewsBlock';
    private static $icon          = 'quickblocks/images/news.png';

    private static $db = [
        'Content' => 'HTMLText'
    ];

    public function getCMSFields()
    {

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

        }

        return $fields;
    }

    public function getLatestNews()
    {

        return BlogPost::get()->Limit(3);
    }
}