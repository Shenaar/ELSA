<?php

namespace App\Service\PathGenerator\Contracts;

use Carbon\Carbon;

/**
 * Used to generate a path for a date.
 */
interface PathGenerator
{
    /**
     * Generates a path for a given date.
     *
     * @param Carbon $date
     *
     * @return string
     */
    public function getForDate(Carbon $date);
}
