<?php

namespace App\Service\CloudCleaner\Contracts;

use App\Service\Photo;

/**
 * Interface for color mappers.
 */
interface ColorMapper
{
    /**
     * @param Photo $photo
     *
     * @return string[][]
     */
    public function map(Photo $photo);
}
