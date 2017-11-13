<?php

namespace App\Service\PathGenerator\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Used to generate all possible paths for a date.
 */
interface PathsGenerator
{
    /**
     * @param Carbon $date
     *
     * @return Collection
     */
    public function getForDate(Carbon $date);
}
