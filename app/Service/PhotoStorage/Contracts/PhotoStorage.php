<?php

namespace App\Service\PhotoStorage\Contracts;

use App\Exceptions\PhotoNotFoundException;
use App\Service\Photo;

use Carbon\Carbon;

/**
 * Returns the file for the date.
 */
interface PhotoStorage
{
    /**
     * @param Carbon $date
     *
     * @return Photo
     *
     * @throws PhotoNotFoundException if a photo cannot be found for the date
     */
    public function getForDate(Carbon $date);
}
