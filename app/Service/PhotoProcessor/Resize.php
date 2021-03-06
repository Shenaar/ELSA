<?php

namespace App\Service\PhotoProcessor;

use App\Service\Photo;
use App\Service\PhotoProcessor\Contracts\PhotoProcessor;

/**
 * Resizes a photo.
 */
class Resize implements PhotoProcessor
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

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
