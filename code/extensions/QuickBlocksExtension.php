<?php

/**
 * Class QuickBlocksExtension
 *
 * @property Page $owner
 */
class QuickBlocksExtension extends DataExtension
{
    private static $many_many = [
        'ContentBlocks' => 'QuickBlock'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        /** -----------------------------------------
         * Blocks
         * ----------------------------------------*/

        $fields->insertAfter('Main', new Tab('Blocks'));

        $config = GridFieldConfig_RelationEditor::create(50);
        $config->addComponent(GridFieldOrderableRows::create('SortOrder'))
//            ->removeComponentsByType('GridFieldDeleteAction')
            ->removeComponentsByType('GridFieldAddNewButton')
            ->addComponent(new GridFieldContentBlockState())
            ->addComponent(new GridFieldArchiveAction());

        $multiClass = new GridFieldAddNewMultiClass();
        $multiClass->setClasses(Config::inst()->get('QuickBlocksExtension', 'available_blocks'));

        $config->addComponent($multiClass);

        $gridField = GridField::create(
            'ContentBlocks',
            'Blocks',
            $this->owner->ContentBlocks(),
            $config
        );

        $fields->addFieldToTab('Root.Blocks', $gridField);
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
 * @property Page_Controller $owner
 */
class QuickBlocksControllerExtension extends Extension
{
    private static $allowed_actions = [
        'QuickBlock'
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
}