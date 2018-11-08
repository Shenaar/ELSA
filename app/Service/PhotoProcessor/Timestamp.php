<?php

namespace App\Service\PhotoProcessor;

use App\Service\Photo;
use App\Service\PhotoProcessor\Contracts\PhotoProcessor;

/**
 * Adds a timestamp to a photo.
 */
class Timestamp implements PhotoProcessor
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var
     */
    private $color;

    /**
     * @var int
     */
    private $fontSize;

    /**
     * Timestamp constructor.
     *
     * @param int   $x
     * @param int   $y
     * @param mixed $color
     * @param int   $fontSize
     */
    public function __construct($x, $y, $color, $fontSize)
    {
        $this->x = $x;
        $this->y = $y;
        $this->color = $color;
        $this->fontSize = $fontSize;
    }

    /**
     * @inheritdoc
     */
    public function process(Photo $photo)
    {
        $image = new \Imagick();
        $image->readImageBlob($photo->getData());

        $draw = new \ImagickDraw();
        $draw->setGravity(\Imagick::GRAVITY_SOUTHEAST);
        $draw->setFillColor($this->color);
        $draw->setFontSize($this->fontSize);
        $draw->setFont('DejaVu-Sans');

        $image->annotateImage($draw, $this->x, $this->y, 0, $photo->getDate()->format('d.m.Y H:i'));

        $photo->setData($image->getImageBlob());

        return $photo;
    }
}
