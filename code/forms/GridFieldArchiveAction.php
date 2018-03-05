<?php

namespace Toast;

use SilverStripe\Forms\GridField\GridField_FormAction;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\Forms\GridField\GridField_ActionProvider;

class GridFieldArchiveAction implements GridField_ColumnProvider, GridField_ActionProvider
{

    public function augmentColumns($gridField, &$columns)
    {
        if (!in_array('Actions', $columns)) {
            $columns[] = 'Actions';
        }
    }

    public function getColumnAttributes($gridField, $record, $columnName)
    {
        return ['class' => 'col-buttons'];
    }

    public function getColumnMetadata($gridField, $columnName)
    {
        if ($columnName == 'Actions') {
            return ['title' => ''];
        }
    }

    public function getColumnsHandled($gridField)
    {
        return ['Actions'];
    }

    public function getColumnContent($gridField, $record, $columnName)
    {
        if (!$record->canEdit()) {
            return;
        }

        $field = GridField_FormAction::create(
            $gridField,
            'ArchiveAction' . $record->ID,
            false,
            "archive",
            ['RecordID' => $record->ID]
        )
            ->addExtraClass('btn btn--no-text btn--icon-md font-icon-cancel-circled grid-field__icon-action gridfield-button-archive')
            ->setAttribute('title', 'Archive')
            ->addExtraClass('gridfield-button-delete');

        return $field->Field();
    }

    public function getActions($gridField)
    {
        return ['archive'];
    }

    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($actionName == 'archive') {
            /** @var QuickBlock|SiteTree $item */
            $item = $gridField->getList()->byID($arguments['RecordID']);
            if (!$item) {
                return;
            }

            if (!$item->canArchive()) {
                throw new ValidationException(
                    _t('GridFieldAction_Delete.DeletePermissionsFailure', "No delete permissions"), 0);
            }

            $item->doArchive();

            Controller::curr()->getResponse()->setStatusCode(
                200,
                'Record archived.'
            );
        }
    }

}