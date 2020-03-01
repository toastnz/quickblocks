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
            'AccordionItem',
            'ContentTab',
            'DownloadBlock',
            'ImageBlock',
            'QuickBlock',
            'SplitBlock',
            'TabbedContentBlock',
            'TestimonialBlock',
            'TextBlock',
            'VideoBlock'
        ];

        $versioned = [
            'AccordionBlock',
            'DownloadBlock',
            'ImageBlock',
            'QuickBlock',
            'SplitBlock',
            'TabbedContentBlock',
            'TestimonialBlock',
            'TextBlock',
            'VideoBlock',
        ];

        foreach ($oldBlockTables as $table) {

            $tableExists = DB::query("SHOW TABLES LIKE '" . $table . "'");

            if ($tableExists->numRecords() != 0) {

                Debug::dump('<div style="padding:5px 10px;background-color:#777;color:#eee;">' . $table . '</div>');

                $sqlQuery = new SQLSelect();
                $sqlQuery->setFrom($table);
                $result = $sqlQuery->execute();

                $insert = SQLInsert::create('"SilverShop_' . $table . '"');

                $x = 1;

                foreach ($result as $row) {

                    Debug::dump('<div style="padding:5px 10px;background-color:#aaa;color:#eee;">' . $row['ID'] . '</div>');
                    Debug::dump($row);

                    $newData = [];

                    foreach ($row as $key => $value) {
                        $newData[$key] = $row[$key];
                    }

                    Debug::dump('SilverShop_Product_Live');
                    Debug::dump($newData);

                    $insert->addRow($newData);
                }

                $insert->execute();

                $x++;
                if ($x > 2) {
//                        die();
                }

            }

        }

        Debug::dump('<div style="padding:5px 10px;background-color:#5ea65e;color:#eee;">Done</div>');

    }

}
