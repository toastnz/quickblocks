<?php

namespace Toast\QuickBlocks;

use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\CheckboxField;

/**
 * Class GalleryBlock
 *
 * @method GalleryImageItem Object
 */
class GalleryBlock extends QuickBlock
{
    private static $singular_name = 'Gallery';
    private static $plural_name   = 'Galleries';
    private static $table_name    = 'GalleryBlock';
    private static $icon          = 'quickblocks/images/gallery.png';

    private static $db = [
        'ShowThumbnail' => 'Boolean',
    ];

    // private static $has_one = [
    //     'CoverImage' => Image::class
    // ];

    // private static $owns = [
    //     'CoverImage'
    // ];

    private static $has_many = [
        'GalleryImages' => GalleryImageItem::class
    ];

    // private static $summary_fields = [
    //     'CoverImage.CMSThumbnail' => 'Image'
    // ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {

            $config = GridFieldConfig_RelationEditor::create(50)
                                                    ->removeComponentsByType(GridFieldAddExistingAutoCompleter::class)
                                                    ->removeComponentsByType(GridFieldDeleteAction::class)
                                                    ->addComponents(new GridFieldDeleteAction())
                                                    ->addComponents(GridFieldOrderableRows::create('SortOrder'));
            $grid = GridField::create(
                'GalleryImages',
                'Gallery Images',
                $this->GalleryImages(),
                $config
            );

            if ($this->ID) {
                $fields->addFieldsToTab('Root.Main', [
                    $fields->insertAfter(
                        'ParentPageID',
                        CheckboxField::create('ShowThumbnail', 'Show Thumbnail')
                    ),

                    // UploadField::create('CoverImage', 'Cover Image')->setFolderName('Uploads/blocks/gallery-images'),
                    $grid
                ]);
            } else {
                $fields->addFieldToTab('Root.Main', LiteralField::create('', '<div class="message notice">Save this block to show additional options.</div>'));
            }
        });

        $fields = parent::getCMSFields();

        return $fields;
    }


}
