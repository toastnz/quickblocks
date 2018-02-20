<?php

/**
 * Class HeroBlock
 *
 * @property string Content
 * @property string Colour
 *
 * @method Image Image
 */
class HeroBlock extends QuickBlock
{
    private static $singular_name = 'Hero Block';
    private static $plural_name = 'Hero Blocks';

    private static $db = [
        'Heading' => 'Varchar(100)',
        'Content' => 'Text',
        'Colour'  => 'Enum("Blue,Purple")'
    ];

    private static $has_one = [
        'Image' => 'Image',
        'Link'  => 'Link'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create([new TabSet('Root')]);

        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title', 'Name')
                ->setAttribute('placeholder', 'This is a helper field only (will not show in templates)'),
            TextField::create('Heading', 'Heading'),
            TextareaField::create('Content', 'Content'),
            LinkField::create('LinkID', 'Link'),
            $this->dbObject('Colour')->formField(),
            UploadField::create('Image', 'Image')
                ->setDescription('Ideal size: 820x520')
        ]);

        return $fields;
    }

    public function getContentSummary()
    {
        return DBField::create_field('HTMLText', $this->Content);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Image', 'Content']);
    }

    public function getColourClass()
    {
        return $this->Colour == 'Purple' ? : 'heroBlock--purple';
    }
}