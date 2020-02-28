<?php

use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLInsert;
use SilverStripe\ORM\Queries\SQLSelect;

class BlockTablesMigrationTask extends BuildTask
{
    private static $segment = 'BlockTablesMigrationTask';

    protected $title = 'QuickBlock Tables Migration';

    protected $description = 'Migrate QuickBlock "TableName" to "QuickBlocks_TableName"';

    public function run($request)
    {
        $oldBlockTables = [
            'AccordionBlock',
            'AccordionBlock_Live',
            'AccordionBlock_Versions',
            'AccordionItem',
            'ContentTab',
            'DownloadBlock',
            'DownloadBlock_Live',
            'DownloadBlock_Versions',
            'ImageBlock',
            'ImageBlock_Live',
            'ImageBlock_Versions',
            'QuickBlock',
            'QuickBlock_Live',
            'QuickBlock_Versions',
            'SplitBlock',
            'SplitBlock_Live',
            'SplitBlock_Versions',
            'TabbedContentBlock',
            'TabbedContentBlock_Live',
            'TabbedContentBlock_Versions',
            'TestimonialBlock',
            'TestimonialBlock_Live',
            'TestimonialBlock_Versions',
            'TextBlock',
            'TextBlock_Live',
            'TextBlock_Versions',
            'VideoBlock',
            'VideoBlock_Live',
            'VideoBlock_Versions'
        ];

        foreach ($oldBlockTables as $table) {

            $tableExists = DB::query("SHOW TABLES LIKE '" . $table . "'");

            if ($tableExists->numRecords() != 0) {

                Debug::dump('<div style="padding:5px 10px;background-color:#777;color:#eee;">' . $table . '</div>');

                $rows = DB::query('SELECT * FROM `' . $table . '`');

                $x = 1;

                foreach ($rows as $row) {

                    Debug::dump('<div style="padding:5px 10px;background-color:#aaa;color:#eee;">' . $row['ID'] . '</div>');
                    Debug::dump($row);

                    $className = 'Toast\QuickBlocks\\' . $table;

                    $newItem = $className::create();

                    foreach ($row as $key => $value) {
                        $newItem->setField($key, $value);
                    }

                    Debug::dump($newItem->toMap());

                    $newItem->write();

                    $x++;
                    if ($x > 2) {
//                        die();
                    }

                }

            }

        }

        Debug::dump('<div style="padding:5px 10px;background-color:#5ea65e;color:#eee;">Done</div>');

    }

}
