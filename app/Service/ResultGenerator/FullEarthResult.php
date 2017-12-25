<?php

namespace App\Service\ResultGenerator;

use App\Service\CloudCleaner\Contracts\CloudCleaner;
use App\Service\Color;
use App\Service\Photo;
use App\Service\ResultGenerator\Contracts\ResultGenerator;

use \Imagick;
use \ImagickDraw;
use \ImagickPixel;

/**
 * Tries to create a full image of the Earth.
 */
class FullEarthResult implements ResultGenerator
{
    /**
     * @var CloudCleaner
     */
    private $cloudCleaner;

    /**
     * FullEarthResult constructor.
     *
     * @param CloudCleaner $cloudCleaner
     */
    public function __construct(CloudCleaner $cloudCleaner)
    {
        $this->cloudCleaner = $cloudCleaner;
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        $this->cloudCleaner->addPhoto($photo);
    }

    /**
     * @inheritdoc
     */
    public function store(string $path)
    {
        $resultMap = $this->cloudCleaner->getResult();

        $width = count($resultMap);
        $height = count($resultMap[0]);

        $img = new Imagick();
        $img->newImage($width, $height, new ImagickPixel('black'));
        $img->setImageFormat('jpg');
        $img->setImageDepth(6);

        $i = 0;

        for ($x = 0; $x < $width; ++$x) {
            $result = new ImagickDraw();
            $result->setResolution($width, $height);
            $result->setFillColor(new ImagickPixel('black'));

            for ($y = 0; $y < $height; ++$y) {
                /** @var Color $color */
                $color = $resultMap[$x][$y];
                ++$i;
                if ($color->toHexString() === '#000000') {
                    continue;
                }

                $drawPixel = new ImagickPixel($color->toHexString());

                $result->setFillColor($drawPixel);
                $result->point($x, $y);
            }

            $img->drawImage($result);

            $result->destroy();
            unset($result);
        }

        $img->writeImage($path . '.jpg');
    }
}
