<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\HasManyList;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Class TabbedContentBlock
 * @package Toast\QuickBlocks
 *
 * @method HasManyList Tabs()
 */
class TabbedContentBlock extends QuickBlock
{
    private static $singular_name = 'Tabbed Content';
    private static $plural_name = 'Tabbed Content';
    private static $table_name = 'TabbedContentBlock';

    private static $icon = 'quickblocks/images/utilityblock.png';

    private static $db = [
        'Colour' => 'Enum("grey,white","white")'
    ];

    private static $has_many = [
        'Tabs' => ContentTab::class
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            if ($this->exists()) {
                $config = GridFieldConfig_RelationEditor::create(50);

                $config->addComponent(GridFieldOrderableRows::create('SortOrder'))
                    ->removeComponentsByType(GridFieldDeleteAction::class)
                    ->addComponent(new GridFieldDeleteAction())
                    ->removeComponentsByType(GridFieldAddExistingAutoCompleter::class);

                $gridField = GridField::create(
                    'Tabs',
                    'Tabs',
                    $this->Tabs(),
                    $config
                );

                $fields->addFieldsToTab('Root.Main', [
                    DropdownField::create('Colour', 'Background Colour', [
                        'white' => 'White',
                        'grey'  => 'Grey'
                    ]),
                    $gridField
                ]);
            } else {
                $fields->addFieldToTab('Root.Main',
                    LiteralField::create('Notice', '<div class="message notice">Save this block to see more options.</div>')
                );
            }
        });

        $fields = parent::getCMSFields();
        return $fields;
    }

    public function getContentSummary()
    {

        $content = '';

        /** @var ContentTab $tab */
        foreach ($this->Tabs() as $tab) {
            $content .= $tab->dbObject('Content')->LimitWordCount(20) . '<br>';
        }

        return DBField::create_field(DBHTMLText::class, $content);
    }
}