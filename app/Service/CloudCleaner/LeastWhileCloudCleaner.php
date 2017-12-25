<?php

namespace App\Service\CloudCleaner;

use App\Service\CloudCleaner\Contracts\ColorMapper;
use App\Service\Color;
use App\Service\Photo;

/**
 * Chooses the least white pixel.
 */
class LeastWhileCloudCleaner extends AbstractCloudCleaner
{
    /**
     * @var Color[][]
     */
    private $resultMap;

    /**
     * @param ColorMapper $colorMapper
     */
    public function __construct(ColorMapper $colorMapper)
    {
        parent::__construct($colorMapper);
    }

    /**
     * @param Photo $photo
     */
    public function addPhoto(Photo $photo)
    {
        $colorMap = $this->mapper->map($photo);

        for ($x = 0, $width = count($colorMap); $x < $width; ++$x) {
            $row = $colorMap[$x];

            if (!isset($this->resultMap[$x])) {
                $this->resultMap[$x] = [];
            }

            for ($y = 0, $height = count($row); $y < $height; ++$y) {
                /** @var Color $color */
                $color = $colorMap[$x][$y];

                if (!isset($this->resultMap[$x][$y])) {
                    $this->resultMap[$x][$y] = $color;

                    continue;
                }

                $oldColor = $this->resultMap[$x][$y];

                if ($this->distanceFromWhite($color) > $this->distanceFromWhite($oldColor)) {
                    $this->resultMap[$x][$y] = $color;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getPixelColor($x, $y)
    {
        return $this->resultMap[$x][$y];
    }

    /**
     * @inheritdoc
     */
    public function getResult()
    {
        return $this->resultMap;
    }

    /**
     * Returns "distance" between the color and white.
     *
     * @param Color $color
     *
     * @return float
     */
    protected function distanceFromWhite(Color $color)
    {
        return sqrt(
            pow(255 - $color->getRed(), 2) +
            pow(255 - $color->getGreen(), 2) +
            pow(255 - $color->getBlue(), 2)
        );
    }
}
