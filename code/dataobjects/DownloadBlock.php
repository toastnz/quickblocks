<?php

namespace Toast\QuickBlocks;

use SilverStripe\Assets\File;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;

/**
 * Class DownloadBlock
 *
 * @method ManyManyList|File[] Files()
 */
class DownloadBlock extends QuickBlock
{
    private static $singular_name = 'Download';
    private static $plural_name = 'Download';
    private static $table_name = 'DownloadBlock';

    private static $many_many = [
        'Files' => File::class
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            UploadField::create('Files', 'Files')
                ->setFolderName('Uploads/downloads')
        ]);

        $this->extend('updateCMSFields', $fields);

        return $fields;
    }

    public function getContentSummary()
    {
        if ($this->Files()) {
            return DBField::create_field('Text', implode(', ', $this->Files()->column('Title')));
        }

        return DBField::create_field('Text', $this->Title);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Files']);
    }

}