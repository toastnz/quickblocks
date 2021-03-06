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
        'Testimonial' => 'Text',
        'Attribution' => 'Varchar(100)',
        'Location'    => 'Varchar(100)'
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
                TextField::create('Location', 'Location')
            ]);
        });

        $fields = parent::getCMSFields();

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