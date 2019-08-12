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
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Permission;
use SilverStripe\ORM\DataObject;
use Jaedb\IconField\Icon;
use Jaedb\IconField\IconField;

/**
 * Class SubNavigationLink
 * @method Link Linkable
 *
 * @method AccordionBlock Parent()
 */
class LinksBlockItem extends DataObject
{

    private static $singular_name = 'LinksBlockItem';
    private static $plural_name = 'LinksBlockItems';
    private static $default_sort = 'SortOrder';
    private static $table_name = 'LinksBlockItem';

    private static $db = [
        'SortOrder' => 'Int',
        'Icon' => Icon::class
    ];

    private static $has_one = [
        'LinksBlock' => LinksBlock::class,
        'PageLink' => Link::class,
    ];

    private static $summary_fields = [
        'PageLink.Title'         => 'Title',
    ];


    public function getTitle()
    {
        return $this->PageLink()->Title;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            IconField::create('Icon', 'Icon', 'Icon'),
            LinkField::create('PageLinkID', 'Page you want the Image to link too')
        ]);

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->SortOrder) {
            $max = (int)Brand::get()->filter(['ParentID' => $this->ParentID])->max('SortOrder');
            $this->setField('SortOrder', $max + 1);
        }
    }

    public function getCMSValidator()
    {
        return new RequiredFields([]);
    }
}