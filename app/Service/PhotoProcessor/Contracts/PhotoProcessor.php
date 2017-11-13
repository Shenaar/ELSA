<?php

namespace App\Service\PhotoProcessor\Contracts;

use App\Service\Photo;

interface PhotoProcessor
{
    /**
     * @param Photo $photo
     *
     * @return Photo
     */
    public function process(Photo $photo);
}
