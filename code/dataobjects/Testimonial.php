<?php
namespace Toast\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\ORM\FieldType\DBField;
use Toast\QuickBlocks\TestimonialBlock;

class Testimonial extends DataObject
{

    private static $db = [
        'SortOrder' => 'Text',
        'Testimonial' => 'Text',
        'Attribution' => 'Varchar(100)',
        'Location'    => 'Varchar(100)'
    ];

    private static $summary_fields = [
        'Testimonial' => 'Testimonial',
        'Attribution' => 'Name',
        'Location' => 'Location'
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
                TextField::create('Location', 'Location/Company')
            ]);
        });

        $fields = parent::getCMSFields();
        $fields->removeByName(['SortOrder']);
        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->Testimonial);
    }

}
