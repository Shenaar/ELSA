<?php

namespace App\Service;

/**
 * Represents a color.
 */
class Color
{
    /**
     * @var int
     */
    private $red;

    /**
     * @var int
     */
    private $green;

    /**
     * @var int
     */
    private $blue;

    /**
     * Color constructor.
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $red, int $green, int $blue)
    {
        if (($red < 0 || $red > 255) ||
            ($green < 0 || $green > 255) ||
            ($blue < 0 || $blue > 255)) {

            throw new \InvalidArgumentException('Color value must be between 0 and 255');
        }

        $this->red   = $red;
        $this->green = $green;
        $this->blue  = $blue;
    }

    /**
     * Returns a HEX string (#11AABB) representing the color.
     *
     * @return string
     */
    public function toHexString()
    {
        return sprintf('#%02X%02X%02X', $this->getRed(), $this->getGreen(), $this->getBlue());
    }

    /**
     * @return int
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * @return int
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * @return int
     */
    public function getBlue(): int
    {
        return $this->blue;
    }
}
