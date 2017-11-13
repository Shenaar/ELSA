<?php

namespace App\Service\PhotoProcessor;

use App\Service\Photo;
use App\Service\PhotoProcessor\Contracts\PhotoProcessor;

/**
 * Adds a timestamp to a photo.
 */
class Resize implements PhotoProcessor
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * Resize constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @inheritdoc
     */
    public function process(Photo $photo)
    {
        $image = new \Imagick();
        $image->readImageBlob($photo->getData());

        $image->resizeImage($this->width, $this->height, \Imagick::FILTER_LANCZOS, 1, true);

        $photo->setData($image->getImageBlob());

        return $photo;
    }
}
