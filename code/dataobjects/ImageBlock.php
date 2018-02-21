<?php

/**
 * Class ImageBlock
 *
 * @property string Size
 *
 * @method Image Image
 */
class ImageBlock extends QuickBlock
{
    private static $singular_name = 'Image';
    private static $plural_name = 'Images';

    private static $has_one = [
        'Image' => 'Image'
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', [
            UploadField::create('Image', 'Image')
                ->setFolderName('Uploads/page-images')
        ]);

        return $fields;
    }

    public function getContentSummary()
    {
        $content = '';

        if ($this->Image() && $this->Image()->exists()) {
            $content = $this->Image()->Fit(300, 150)->forTemplate();
        }
        return DBField::create_field('HTMLText', $content);
    }

    public function getCMSValidator()
    {
        return new RequiredFields(['Image']);
    }
}