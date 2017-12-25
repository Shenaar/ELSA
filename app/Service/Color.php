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
     * @param int $red   Red color value between 0 and 255
     * @param int $green Green color value between 0 and 255
     * @param int $blue  Blue color value between 0 and 255
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
     * Creates a Color from an [r, g, b] array.
     *
     * @param array $array
     *
     * @return Color
     */
    public static function fromArray(array $array)
    {
        return new static($array['r'], $array['g'], $array['b']);
    }

    /**
     * Creates a Color from a string "#RRGGBB".
     *
     * @param string $hex
     *
     * @return Color
     */
    public static function fromHex(string $hex)
    {
        return new static(
            hexdec($hex[1] . $hex[2]),
            hexdec($hex[3] . $hex[4]),
            hexdec($hex[5] . $hex[6])
        );
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
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @return int
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * @return int
     */
    public function getBlue()
    {
        return $this->blue;
    }
}
