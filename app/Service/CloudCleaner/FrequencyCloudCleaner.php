<?php

namespace App\Service\CloudCleaner;

use App\Service\Color;
use App\Service\ColorMapper\Contracts\ColorMapper;
use App\Service\Photo;

/**
 * Removes clouds using the most frequent color for a pixel.
 */
class FrequencyCloudCleaner extends AbstractCloudCleaner
{
    /**
     * @var PixelFrequency[][]
     */
    private $frequencyMap;

    /**
     * @param ColorMapper $mapper
     */
    public function __construct(ColorMapper $mapper)
    {
        parent::__construct($mapper);
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        $colorMap = $this->mapper->map($photo);

        for ($x = 0, $width = count($colorMap); $x < $width; ++$x) {
            $row = $colorMap[$x];

            if (!isset($this->frequencyMap[$x])) {
                $this->frequencyMap[$x] = [];
            }

            for ($y = 0, $height = count($row); $y < $height; ++$y) {

                if (!isset($this->frequencyMap[$x][$y])) {
                    $this->frequencyMap[$x][$y] = new PixelFrequency();
                }

                /** @var Color $color */
                $color = $colorMap[$x][$y];

                $pixel = $this->frequencyMap[$x][$y];
                $pixel->addOccurance($color);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getPixelColor($x, $y)
    {
        return $this->frequencyMap[$x][$y]->getMostFrequent();
    }

    /**
     * @inheritdoc
     */
    public function getResult()
    {
        $result = [];

        for ($x = 0, $width = count($this->frequencyMap); $x < $width; ++$x) {
            $row = $this->frequencyMap[$x];

            if (!isset($result[$x])) {
                $result[$x] = [];
            }

            for ($y = 0, $height = count($row); $y < $height; ++$y) {

                /** @var Color $color */
                $color = $this->getPixelColor($x, $y);

                $result[$x][$y] = $color;
            }
        }

        return $result;
    }


}
