<?php

namespace App\Service\ColorMapper\Contracts;

use App\Service\Color;
use App\Service\Photo;

/**
 * Interface for color mappers.
 */
interface ColorMapper
{
    /**
     * @param Photo $photo
     *
     * @return Color[][]
     */
    public function map(Photo $photo);
}
