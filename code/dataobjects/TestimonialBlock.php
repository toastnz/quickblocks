<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;
use Toast\Model\Testimonial;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;


/**
 * Class TestimonialBlock
 *
 * @property string Testimonial
 */
class TestimonialBlock extends QuickBlock
{
    private static $singular_name = 'Testimonial Block';
    private static $plural_name   = 'Testimonial Blocks';
    private static $table_name    = 'TestimonialBlock';
    private static $icon          = 'quickblocks/images/testimonial.png';

    private static $db = [
        'Testimonial' => 'Text',
        'Attribution' => 'Varchar(100)',
        'Location'    => 'Varchar(100)',
        'ShowSlider'      => 'Boolean'
    ];

    private static $many_many = [
        'Testimonials' => Testimonial::class,
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {

            $fields->addFieldsToTab('Root.Main', [
                TextareaField::create('Testimonial', 'Testimonial'),
                TextField::create('Attribution', 'Name'),
                TextField::create('Location', 'Location'),
                CheckboxField::create('ShowSlider', 'Show Slider'),
            ]);

            $conf = GridFieldConfig_RelationEditor::create(10);
            $conf->addComponent(new GridFieldSortableRows('SortOrder'));

            $fields->addFieldsToTab('Root.Testimonials', [
                GridField::create('Testimonials', 'Testimonials', $this->owner->Testimonials(), $conf)
            ]);

        });

        $fields = parent::getCMSFields();

        $fields->removeByName(['Attribution', 'Testimonial', 'Location']);

        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->Testimonial);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Testimonial']);
    }
}