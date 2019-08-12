<?php

namespace Toast\QuickBlocks;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\FieldList;
use SilverStripe\Security\Permission;
use SilverStripe\ORM\DataObject;

/**
 * Class Testimonial
 *
 * @property string $FirstName
 * @property string $Surname
 * @property string $Email
 * @property string $Phone
 * @property string $Message
 */
class Testimonial extends DataObject
{
    private static $table_name = 'Testimonial';

    private static $db = [
        'SortOrder' => 'Int',
        'Name' => 'Varchar(500)',
        'Company'   => 'Varchar(500)',
        'Role'     => 'Varchar(500)',
        'Message'   => 'Text'
    ];
    

    private static $belongs_many_many = [
        'TestimonialBlocks' => TestimonialBlock::class
    ];
}
