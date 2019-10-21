<?php
/**
 * Created by PhpStorm.
 * User: staff
 * Date: 1/02/19
 * Time: 3:20 PM
 */

namespace Toast\QuickBlocks;


use Sheadawson\Linkable\Models\Link;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Toast\Model\LinkBlockItem;


class LinkBlock extends QuickBlock
{
    private static $singular_name = 'Link';
    private static $plural_name = 'Links';
    private static $table_name = 'LinkBlock';
    private static $icon = 'quickblocks/images/image.png';

    private static $db = [
        'Title'     => 'Varchar(100)',
        'Content'   => 'HTMLText',
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