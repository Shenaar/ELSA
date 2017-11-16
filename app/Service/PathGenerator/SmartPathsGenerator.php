<?php

namespace App\Service\PathGenerator;

use App\Service\PathGenerator\Contracts\PathGenerator;
use App\Service\PathGenerator\Contracts\PathsGenerator;

use Carbon\Carbon;

use Illuminate\Support\Collection;

/**
 * "Smart choosing" a generator.
 * The naming was changed on 1 June 2017.
 */
class SmartPathsGenerator implements PathsGenerator
{
    /**
     * @var PathGenerator[]|Collection
     */
    private $generators;

    /**
     * PathsGenarator constructor.
     *
     * @param PathGenerator[] $generators
     */
    public function __construct(array $generators)
    {
        $this->generators = collect($generators);
    }

    /**
     * @inheritdoc
     */
    public function getForDate(Carbon $date)
    {
        $theFirstDate = new Carbon('24.02.2017');
        $startNew = new Carbon('01.06.2017');

        if ($date->isFuture() || $date->lessThan($theFirstDate)) {
            return collect();
        }

        if ($date->lessThan($startNew)) {
            return collect($this->generators[1]->getForDate($date));
        }

        return collect($this->generators[0]->getForDate($date));
    }
}
