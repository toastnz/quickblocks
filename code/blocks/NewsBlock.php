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

class NewsBlock extends QuickBlock
{
    private static $singular_name = 'News Block';
    private static $plural_name = 'News Blocks';
    private static $table_name = 'NewsBlock';

    private static $db = [
        'Heading' => 'Text'
    ];

    private static $has_one = [
        'NewsPage' => SiteTree::class,
    ];


    /**
     * @return FieldList
     */
    public function getCMSFields()
    {

        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', TreeDropdownField::create('NewsPageID', 'News Page', SiteTree::class ));
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
}