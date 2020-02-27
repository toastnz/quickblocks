<?php

use SilverStripe\Dev\BuildTask;
use SilverStripe\Dev\Debug;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLInsert;
use SilverStripe\ORM\Queries\SQLSelect;

class BlockTablesMigrationTask extends BuildTask
{
    private static $segment = 'BlockTablesMigrationTask';

    protected $title = 'QuckBlock Tables Migration';

    protected $description = 'Migrate QuckBlock "TableName" to "QuickBlocks_TableName"';

    public function run($request)
    {

        $tables = [
            'AccordionBlock',
            'AccordionItem',
            'ChildLinkBlock',
            'ColumnBlock',
            'ColumnBlockItem',
            'ContentTab',
            'DownloadBlock',
            'GalleryBlock',
            'GalleryImageItem',
            'HeroBlock',
            'ImageBlock',
            'LinkBlock',
            'LinkBlockItem',
            'NewsBlock',
            'NewsBlockItem',
            'PercentageBlock',
            'QuickBlock',
            'SplitBlock',
            'TabbedContentBlock',
            'Testimonial',
            'TestimonialBlock',
            'TextBlock',
            'VideoBlock'
        ];

        foreach ($tables as $table) {

            $rows = DB::query('SELECT * FROM "' . $table . '"');

            foreach ($rows as $row) {

                Debug::dump($row);
                die();

                $newItem = BannerSliderItem::create();

                $newItem->setField('ID', $sliderItem['ID']);
                $newItem->setField('LastEdited', $sliderItem['LastEdited']);
                $newItem->setField('Created', $sliderItem['Created']);
                $newItem->setField('Content', $sliderItem['Caption']);
                $newItem->setField('SortOrder', $sliderItem['SortOrder']);
                $newItem->setField('YouTubeCode', $sliderItem['YouTubeCode']);
                $newItem->setField('UseVideo', $sliderItem['UseVideo']);
                $newItem->setField('VideoButtonText', $sliderItem['VideoButtonText']);
                $newItem->setField('UseAnimation', $sliderItem['UseAnimation']);
                $newItem->setField('ImageID', $sliderItem['ImageID']);
                $newItem->setField('PageID', $sliderItem['PageID']);

                Debug::dump($newItem);

                $newItem->write();
            }


            Debug::dump('Done');

        }

    }

}
