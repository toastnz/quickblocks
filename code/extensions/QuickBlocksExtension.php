<?php

namespace Toast\QuickBlocks;

use finfo;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Versioned\VersionedGridFieldItemRequest;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Assets\File;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Extension;
use ZipArchive;

/**
 * Class QuickBlocksExtension
 *
 * @property Page $owner
 */
class QuickBlocksExtension extends DataExtension
{
    private static $many_many = [
        'ContentBlocks' => QuickBlock::class
    ];

    private static $many_many_extraFields = [
        'ContentBlocks' => [
            'SortOrder' => 'Int'
        ]
    ];

    public function updateCMSFields(FieldList $fields)
    {
        /** -----------------------------------------
         * Blocks
         * ----------------------------------------*/

        if ($this->owner->exists()) {
            $fields->insertAfter('Main', new Tab('Blocks'));

            $config = GridFieldConfig_RelationEditor::create(50);
            $config->addComponent(GridFieldOrderableRows::create('SortOrder'))
    //            ->removeComponentsByType('GridFieldDeleteAction')
                ->removeComponentsByType(GridFieldAddNewButton::class)
                ->addComponent(new GridFieldContentBlockState())
                ->addComponent(new GridFieldArchiveAction());
            $config->getComponentByType(GridFieldDetailForm::class)
                ->setItemRequestClass(VersionedGridFieldItemRequest::class);

            $multiClass = new GridFieldAddNewMultiClass();
            $multiClass->setClasses(Config::inst()->get(QuickBlocksExtension::class, 'available_blocks'));

            $config->addComponent($multiClass);

            $gridField = GridField::create(
                'ContentBlocks',
                'Blocks',
                $this->owner->ContentBlocks(),
                $config
            );

            $fields->addFieldToTab('Root.Blocks', $gridField);
        }
    }

    /**
     * Publish all content blocks
     */
    public function onAfterPublish()
    {
        // Loop through content blocks and publish
        /** @var QuickBlock $contentBlock */
        foreach ($this->owner->ContentBlocks() as $contentBlock) {
            if ($contentBlock->canPublish()) {
                $contentBlock->publish('Stage', 'Live');
            }
        }
    }
}

/**
 * Class QuickBlocksControllerExtension
 *
 * @property PageController $owner
 */
class QuickBlocksControllerExtension extends Extension
{
    private static $allowed_actions = [
        'QuickBlock',
        'download'
    ];

    /**
     * Returns content block in template
     *
     * @return HTMLText|string
     */
    public function contentblock()
    {
        if (Director::is_ajax()) {

            $id = $this->owner->getRequest()->param('ID');

            /** @var QuickBlock $contentBlock */
            $contentBlock = QuickBlock::get()->byID($id);

            if ($contentBlock && $contentBlock->exists()) {
                return $contentBlock->forTemplate();
            }
        }

        return $this->owner->redirect($this->owner->Link());
    }


    /**
     * @param SS_HTTPRequest $request
     * @return SS_HTTPResponse
     */
    public function download(HTTPRequest $request)
    {
        /** =========================================
         * @var File $file
        ===========================================*/

        // check if we have an array of IDs
        if ($request->requestVar('files')) {
            $ids = explode(',', $request->requestVar('files'));

            return $this->getZipResponse($ids);
        }

        return null;
    }

    /**
     * @param array $ids
     * @return HTTPResponse
     */
    public function getZipResponse($ids)
    {
        $files = File::get()->byIDs($ids);

        if ($files && $files->exists()) {
            if (count($ids) === 1) {
                $file = $files->first();
                $path = Controller::join_links(Director::baseFolder(), $file->Link());

                header('Content-Type: ' . _mime_content_type($path));
                header('Content-disposition: attachment; filename=' . $file->Name);
                header('Content-Length: ' . filesize($path));
                readfile($path);

            } else {
                $dir = sys_get_temp_dir() . '/archives';
                if (!file_exists($dir)) {
                    mkdir($dir);
                }
                $path   = tempnam($dir, 'h_');
                $result = $this->create_zip($files, $path, true);

                header('Content-Type: application/zip');
                header('Content-disposition: attachment; filename=download.zip');
                header('Content-Length: ' . filesize($path));

                if ($result === true) {
                    return HTTPRequest::send_file(
                        file_get_contents($path),
                        'download.zip',
                        'application/zip'
                    );
                }
            }
        }

        return null;
    }

    /**
     * @link http://davidwalsh.name/create-zip-php
     *
     * @param DataList   $files
     * @param string     $destination
     * @param bool|false $overwrite
     * @return bool
     */
    private function create_zip($files, $destination = '', $overwrite = false)
    {
        //if the zip file already exists and overwrite is false, return false
        if (file_exists($destination) && !$overwrite) {
            return false;
        }
        //vars
        $valid_files = [];
        //if files were passed in...
        //cycle through each file
        foreach ($files->getIterator() as $file) {
            //make sure the file exists
            if (file_exists(Controller::join_links(Director::baseFolder(), $file->Link()))) {
                $valid_files[] = $file;
            }
        }

        //if we have good files...
        if (count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();

            if ($result = $zip->open($destination, ZIPARCHIVE::OVERWRITE) !== true) {

                if (!is_resource($result)) {
                    return false;
                }
            }

            //add the files
            foreach ($valid_files as $file) {
                $zip->addFile(Controller::join_links(Director::baseFolder(), $file->Link()), $file->Name);
            }
            $zip->close();

            return file_exists($destination);
        } else {
            return false;
        }
    }
}

function _mime_content_type($filename)
{
    $result = new finfo();

    if (is_resource($result) === true) {
        return $result->file($filename, FILEINFO_MIME_TYPE);
    }

    return false;
}
