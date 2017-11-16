<?php

namespace App\Service\PhotoProcessor;

use App\Service\Photo;
use App\Service\PhotoProcessor\Contracts\PhotoProcessor;

/**
 * Filters empty photos (sometime they're just fully black).
 */
class FilterEmpty implements PhotoProcessor
{
    /**
     * @inheritdoc
     */
    public function process(Photo $photo)
    {
        $image = new \Imagick();
        $image->readImageBlob($photo->getData());
        $colors = $image->getImageColors();

        if ($colors < 10) {
            return null;
        }

        return $photo;
    }
}
