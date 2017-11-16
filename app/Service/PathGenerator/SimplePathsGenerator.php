<?php

namespace App\Service\PathGenerator;

use App\Service\PathGenerator\Contracts\PathGenerator;
use App\Service\PathGenerator\Contracts\PathsGenerator;

use Carbon\Carbon;

use Illuminate\Support\Collection;

/**
 * Just iterates over every available generator.
 */
class SimplePathsGenerator implements PathsGenerator
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
        $result = $this->generators->map(function (PathGenerator $generator) use ($date) {
            return $generator->getForDate($date);
        });

        return $result;
    }
}
