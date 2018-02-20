<?php

/**
 * Class FileExtension
 *
 * @property File $owner
 */
class FileExtension extends DataExtension
{
    public function getFileInfo()
    {
        return sprintf("%s %s", strtoupper($this->owner->getExtension()), str_replace(' ', '', strtoupper($this->owner->getSize())));
    }

    public function getDownloadLink()
    {
        return Controller::join_links(Controller::curr()->Link(), 'download') . '?files=' . $this->owner->ID;
    }

    public function DownloadLink()
    {
        return $this->getDownloadLink();
    }
}