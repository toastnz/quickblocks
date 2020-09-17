<?php

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use Toast\QuickBlocks\QuickBlock;

class CmsActionsExtension extends DataExtension
{
    /**
     * @param FieldList $actions
     * @return void
     */
    public function updateFormActions(FieldList $actions)
    {
        $record = $this->owner->getRecord();

        // This extension would run on every GridFieldDetailForm, so ensure you ignore contexts where
        // you are managing a DataObject you don't care about
        if (!$record instanceof QuickBlock || !$record->exists()) {
            return;
        }

        $actions->removeByName('new-record');
    }
}
