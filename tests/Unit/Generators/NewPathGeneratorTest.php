<?php

use App\Service\PathGenerator\Generators\NewPathGenerator;

class NewPathGeneratorTest extends \Tests\Unit\TestCase
{
    /**
     * @covers NewPathGenerator::getForDate()
     */
    public function testForDate()
    {
        $generator = new NewPathGenerator();

        $date = (new \Carbon\Carbon())
            ->year(2017)
            ->month(8)
            ->day(11)
            ->hour(10)
            ->minute(30);

        $result = $generator->getForDate($date);

        $this->assertEquals('/ELECTRO_L_2/2017/August/11/1030/170811_1030_RGB.jpg', $result);
    }
}
