<?php

namespace App\Service\CloudCleaner\Contracts;

use App\Service\Color;
use App\Service\Photo;

/**
 * Interface for a processor that can remove clouds using a set of photos.
 */
interface CloudCleaner
{
    /**
     * Adds a photo to the set to process.
     *
     * @param Photo $photo
     *
     * @return void
     */
    public function addPhoto(Photo $photo);

    /**
     * Returns a color of the pixel by it's coordinates.
     *
     * @param int $x
     * @param int $y
     *
     * @return Color Color of the pixel
     */
    public function getPixelColor($x, $y);

    /**
     * @return Color[][] Resulting map of the colors
     */
    public function getResult();
}
