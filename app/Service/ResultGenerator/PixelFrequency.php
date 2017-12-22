<?php

namespace App\Service\ResultGenerator;

use Illuminate\Support\Collection;

/**
 *
 */
class PixelFrequency
{
    /**
     * @var int[]|Collection
     */
    private $frequencyMap;

    /**
     * PixelFrequency constructor.
     */
    public function __construct()
    {
        $this->frequencyMap = new Collection();
    }

    /**
     * @param string
     */
    public function addOccurance($color)
    {
        $frequency = $this->frequencyMap->get($color, 0) + 1;
        $this->frequencyMap->put($color, $frequency);
    }

    /**
     * @return string Color
     */
    public function getMostFrequent()
    {
       $max = [
           'color' => null,
           'count' => 0
       ];

        $this->frequencyMap->each(function ($value, $key) use (&$max) {
            if ($value > $max['count']) {
                $max['color'] = $key;
                $max['count'] = $value;
            }
        });

        return $max['color'];
    }
}
