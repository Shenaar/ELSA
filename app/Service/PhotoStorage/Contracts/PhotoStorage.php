<?php

namespace App\Service\PhotoStorage\Contracts;

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
     */
    public function getForDate(Carbon $date);
}
