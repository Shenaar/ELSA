<?php

namespace App\Service\CloudCleaner;

use App\Service\Color;
use App\Service\ColorMapper\Contracts\ColorMapper;
use App\Service\Photo;

/**
 * Chooses the least white pixel.
 */
class LeastWhiteCloudCleaner extends AbstractCloudCleaner
{
    /**
     * @var Color[][]
     */
    private $resultMap;

    /**
     * @var float
     */
    private $blackWeight;

    /**
     * @var float
     */
    private $whiteWeight;

    /**
     * @param ColorMapper $colorMapper
     * @param float $blackWeight
     * @param float $whiteWeight
     */
    public function __construct(ColorMapper $colorMapper, float $blackWeight = 0.0, float $whiteWeight = 1.0)
    {
        parent::__construct($colorMapper);

        $this->blackWeight = $blackWeight;
        $this->whiteWeight = $whiteWeight;
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

                if ($this->getWeight($color) > $this->getWeight($oldColor)) {
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
     * Returns weight of the color.
     *
     * @param Color $color
     *
     * @return float
     */
    protected function getWeight(Color $color)
    {
        $white =
            $this->whiteWeight * sqrt(
            pow(255 - $color->getRed(), 2) +
                pow(255 - $color->getGreen(), 2) +
                pow(255 - $color->getBlue(), 2)

            );
        $black =
            $this->blackWeight * sqrt(
                pow(0 - $color->getRed(), 2) +
                pow(0 - $color->getGreen(), 2) +
                pow(0 - $color->getBlue(), 2)
            );

        return $white + $black;
    }
}
