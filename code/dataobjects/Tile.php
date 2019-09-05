<?php

namespace Toast\QuickBlocks;

use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Permission;
use SilverStripe\ORM\DataObject;
use Jaedb\IconField\Icon;
use Jaedb\IconField\IconField;
use SilverStripe\Forms\NumericField;

/**
 * Class SubNavigationLink
 * @method Link Linkable
 *
 * @method AccordionBlock Parent()
 */
class Tile extends DataObject
{

    private static $singular_name = 'Item';
    private static $plural_name = 'Item';
    private static $default_sort = 'SortOrder';
    private static $table_name = 'Tile';

    private static $db = [
        'SortOrder' => 'Int',
        'Icon' => Icon::class,
        'Title' => 'Text',
        'Content' => 'HTMLText',
    ];

    private static $has_one = [
        'ColumnsBlock' => TileBlock::class,
        'PageLink' => Link::class,
    ];

    private static $summary_fields = [
        'Title'         => 'Title',
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            
            TextField::create('Title', 'Title'),
            HTMLEditorField::create('Content', 'Content'),
            LinkField::create('PageLinkID', 'Page you want the Image to link too'),
            IconField::create('Icon', 'Icon', '/mysite/dist/images/icons/')
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

       
    }

    public function getCMSValidator()
    {
        return new RequiredFields([]);
    }
}
