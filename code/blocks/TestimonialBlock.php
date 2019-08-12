<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Forms\RequiredFields;

/**
 * Class TestimonialBlock
 *
 * @property string Testimonial
 */
class TestimonialBlock extends QuickBlock
{
    private static $singular_name = 'Testimonial';
    private static $plural_name = 'Testimonials';
    private static $table_name = 'TestimonialBlock';

    private static $db = [
        'Heading' => 'Text'
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
            $config = GridFieldConfig_RelationEditor::create(50)
                ->removeComponentsByType(GridFieldDeleteAction::class)
                ->addComponents(new GridFieldDeleteAction())
                ->addComponents(GridFieldOrderableRows::create('SortOrder'));

            $grid = GridField::create('Testimonials', 'Testimonials', $this->Testimonials(), $config);

            if ($this->ID) {
                $fields->addFieldToTab('Root.Main', $grid);
            } else {
                $fields->addFieldToTab('Root.Main', LiteralField::create('', '<div class="message notice">Save this block to show additional options.</div>'));
            }
        });

        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Heading', 'Heading'),
        ]);

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