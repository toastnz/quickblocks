<?php

namespace Toast\QuickBlocks;


use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextField;
use SilverStripe\View\SSViewer;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class TileBlock extends QuickBlock
{
    private static $singular_name = 'Tile Block';
    private static $plural_name = 'Tile Blocks';
    private static $table_name = 'TileBlock';

    private static $db = [
        'ColumnsCount' => 'Int',
    ];

    private static $defaults = [
        'ColumnsCount' => '4',
    ];


    private static $has_many = [
        'Tiles' => Tile::class,
    ];
    
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $config = GridFieldConfig_RelationEditor::create(50)
                ->removeComponentsByType(GridFieldAddExistingAutoCompleter::class)
                ->removeComponentsByType(GridFieldDeleteAction::class)
                ->addComponents(new GridFieldDeleteAction())
                ->addComponents(GridFieldOrderableRows::create('SortOrder'));

            $grid = GridField::create('Tiles', 'Tile', $this->Tiles(), $config);
        $fields->addFieldsToTab('Root.Main', [
            DropdownField::create('ColumnsCount', 'Columns', [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '11' => '11',
                '12' => '12',
            ]),

        ]);

        $fields->addFieldToTab('Root.Main', $grid);


        return $fields;
    }
}
