<?php

namespace App\Service\CloudCleaner;

use App\Service\Color;

/**
 *
 */
class PixelFrequency
{
    /**
     * @var string[]
     */
    private $frequencyMap;

    /**
     * PixelFrequency constructor.
     */
    public function __construct()
    {
        $this->frequencyMap = [];
    }

    /**
     * @param Color $color
     */
    public function addOccurance(Color $color)
    {
        $this->frequencyMap[] = $color->toHexString();
    }

    /**
     * @return Color
     */
    public function getMostFrequent()
    {
        $color = collect(array_count_values($this->frequencyMap))
            ->sort()
            ->keys()
            ->last();

        return Color::fromHex($color);
    }
}
