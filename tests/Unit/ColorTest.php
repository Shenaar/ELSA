<?php
/**
 * Created by PhpStorm.
 * User: Shenaar
 * Date: 23.12.2017
 * Time: 17:30
 */

namespace Tests\Unit;

use App\Service\Color;

/**
 * @covers Color
 */
class ColorTest extends TestCase
{
    public function testConstructor()
    {
        $color = new Color(0, 128, 255);

        $this->assertEquals(0, $color->getRed());
        $this->assertEquals(128, $color->getGreen());
        $this->assertEquals(255, $color->getBlue());
    }

    public function testConstructorException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $color = new Color(0, 128, -255);
        $color = new Color(0, 1280, 255);
    }

    public function testToHexString()
    {
        $color = new Color(0, 128, 255);

        $this->assertEquals('#0080FF', $color->toHexString());
    }
}
