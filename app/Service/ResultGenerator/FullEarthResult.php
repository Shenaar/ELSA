<?php

namespace App\Service\ResultGenerator;

use App\Service\Photo;
use App\Service\ResultGenerator\Contracts\ResultGenerator;

use Illuminate\Support\Collection;

use \Imagick;
use \ImagickDraw;
use \ImagickPixel;

/**
 * Tries to create a full image of the Earth.
 */
class FullEarthResult implements ResultGenerator
{
    /**
     * @var PixelFrequency[][]
     */
    private $frames;

    /**
     * FullEarthResult constructor.
     */
    public function __construct()
    {
        $this->frames = [];
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        $imagick = new Imagick();

        $imagick->readImageBlob($photo->getData());

        $size = $imagick->getImageGeometry();

        for ($x = 0; $x < $size['width']; ++$x) {
            for ($y = 0; $y < $size['height']; ++$y) {
                $color = $imagick->getImagePixelColor($x, $y)->getColor();
                $hex = '#' . sprintf('%02x%02x%02x', $color['r'], $color['g'], $color['b']);

                if (!isset($this->frames[$x])) {
                    $this->frames[$x] = [];
                }

                if (!isset($this->frames[$x][$y])) {
                    $this->frames[$x][$y] = new PixelFrequency();
                }

                $pixel = $this->frames[$x][$y];
                $pixel->addOccurance($hex);
            }
        }

        $imagick->destroy();
    }

    /**
     * @inheritdoc
     */
    public function store(string $path)
    {
        $img = new Imagick();
        $img->newImage(count($this->frames), count($this->frames[0]), new ImagickPixel('black'));
        $img->setImageFormat('jpg');
        $img->setImageDepth(1);

        $i = 0;
        foreach ($this->frames as $x => $row) {
            $result = new ImagickDraw();
            $result->setResolution(count($this->frames), count($this->frames[0]));
            $result->setFillColor(new ImagickPixel('black'));

            foreach ($row as $y => $pixel) {
                ++$i;
                if ($pixel->getMostFrequent() === '#000000') {
                    continue;
                }

                $drawPixel = new ImagickPixel($pixel->getMostFrequent());

                $result->setFillColor($drawPixel);
                $result->point($x, $y);
            }

            $img->drawImage($result);

            $result->destroy();
            unset($result);

            echo ($i) . ' of ' . (count($this->frames) * count($this->frames[0])) . PHP_EOL;
        }

        $img->writeImage($path . '.jpg');
    }


}
