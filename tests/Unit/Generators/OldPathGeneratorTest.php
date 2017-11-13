<?php

use App\Service\PathGenerator\Generators\OldPathGenerator;

class OldPathGeneratorTest extends \Tests\Unit\TestCase
{
    /**
     * @covers OldPathGenerator::getForDate()
     */
    public function testForDate()
    {
        $generator = new OldPathGenerator();

        $date = (new \Carbon\Carbon())
            ->year(2017)
            ->month(8)
            ->day(11)
            ->hour(10)
            ->minute(30);

        $result = $generator->getForDate($date);

        $this->assertEquals('/ELECTRO_L_2/2017/August/11_08_2017/11082017_10 30.jpg', $result);
    }
}
