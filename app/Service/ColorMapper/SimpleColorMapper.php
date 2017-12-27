<?php

namespace App\Service\ColorMapper;

use App\Service\Color;
use App\Service\ColorMapper\Contracts\ColorMapper;
use App\Service\Photo;

use \Imagick;

/**
 * Iterates over every pixel and gets it's color using \Imagick
 */
class SimpleColorMapper implements ColorMapper
{
    /**
     * @inheritdoc
     */
    public function map(Photo $photo)
    {
        $imagick = new Imagick();

        $imagick->readImageBlob($photo->getData());

        $size = $imagick->getImageGeometry();
        $map = [];

        for ($x = 0; $x < $size['width']; ++$x) {
            $map[$x] = [];

            for ($y = 0; $y < $size['height']; ++$y) {
                $color = Color::fromArray($imagick->getImagePixelColor($x, $y)->getColor());
                $map[$x][$y] = $color;
            }
        }

        $imagick->destroy();

        return $map;
    }
}
