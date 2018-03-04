<?php

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
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            TextareaField::create('Testimonial', 'Testimonial'),
            TextField::create('Attribution', 'Name'),
            TextField::create('Location', 'Location')
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

    /* ==========================================
     * SEARCH
     * ========================================*/


    public function getShowInSearch() {
        return 1;
    }

    public function getAbstract()
    {
        return $this->getContentSummary()->forTemplate();
    }
}