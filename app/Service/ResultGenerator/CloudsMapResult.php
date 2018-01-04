<?php

namespace App\Service\ResultGenerator;

use App\Service\Color;
use App\Service\ColorMapper\Contracts\ColorMapper;
use App\Service\Photo;
use App\Service\ResultGenerator\Contracts\ResultGenerator;

use Illuminate\Filesystem\Filesystem;

use Imagick;
use ImagickDraw;
use ImagickPixel;

/**
 *
 */
class CloudsMapResult implements ResultGenerator
{
    /**
     * @var float
     */
    private $treshold;
    /**
     * @var ColorMapper
     */
    private $colorMapper;

    /**
     * @param float $treshold
     * @param ColorMapper $colorMapper
     */
    public function __construct(ColorMapper $colorMapper, float $treshold = 0.60)
    {
        $this->treshold = $treshold;
        $this->colorMapper = $colorMapper;
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        $colorMap = $this->colorMapper->map($photo);

        $width = count($colorMap);
        $height = count($colorMap[0]);

        /** @var Filesystem $fs */
        $fs = app(Filesystem::class);

        if (!$fs->exists(storage_path('cloudMaps/' . $photo->getDate()->toDateTimeString()))) {
            $fs->makeDirectory(storage_path('cloudMaps/' . $photo->getDate()->toDateTimeString()));
        }

        $img = new Imagick();
        $img->newImage($width, $height, new ImagickPixel('black'));
        $img->setImageFormat('jpg');
        $img->setImageDepth(6);

        for ($x = 0; $x < $width; ++$x) {
            $result = new ImagickDraw();
            $result->setResolution($width, $height);
            $result->setFillColor(new ImagickPixel('black'));

            for ($y = 0; $y < $height; ++$y) {
                /** @var Color $color */
                $color = $colorMap[$x][$y];

                $weight = $this->getWeight($color);

                if ($weight < $this->treshold) {
                    $color = new Color($weight * 255, 0, 0);
                }

                $drawPixel = new ImagickPixel($color->toHexString());

                $result->setFillColor($drawPixel);

                $result->point($x, $y);
            }

            $img->drawImage($result);

            $result->clear();
            unset($result);
        }

        $img->writeImage(storage_path('cloudMaps/' . $photo->getDate()->toDateTimeString() . '/' .
            sprintf('%.2f', $this->treshold) . '.jpg')
        );
        $img->clear();
        unset($img);
    }

    /**
     * @param Color $color
     *
     * @return float
     */
    private function getWeight(Color $color)
    {
        return sqrt(
            pow(255 - $color->getRed(), 2) +
            pow(255 - $color->getGreen(), 2) +
            pow(255 - $color->getBlue(), 2)
        ) / sqrt(255 * 255 * 3);
    }

    /**
     * @inheritdoc
     */
    public function store(string $path)
    {
        return;
    }

}
