<?php

namespace Toast\QuickBlocks;

use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;
use SilverStripe\View\Requirements;
use Toast\QuickBlocks\QuickBlock;
use Toast\QuickBlocks\QuickBlocksExtension;

class SiteTreeExtension extends DataExtension
{
    private static $db = [
        'SearchContent' => 'HTMLText'
    ];

    public function onBeforeWrite()
    {
        if ($this->owner->hasExtension(QuickBlocksExtension::class)) {

            // Copy widgets content to Content to enable search
            $searchableContent = [];

            Requirements::clear();

            /** @var QuickBlock $contentBlock */
            foreach ($this->owner->ContentBlocks()->sort('SortOrder') as $contentBlock) {
                if (Config::inst()->get($contentBlock->ClassName, 'exclude_from_search')) {
                    continue;
                }


                array_push($searchableContent, $contentBlock->forTemplate());
            }

            Requirements::restore();

            $this->owner->setField('SearchContent', trim(implode(' ', $searchableContent)));
        }

    }
}