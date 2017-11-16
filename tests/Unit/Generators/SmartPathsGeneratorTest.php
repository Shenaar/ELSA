<?php

use App\Service\PathGenerator\Generators\NewPathGenerator;
use App\Service\PathGenerator\Generators\OldPathGenerator;
use App\Service\PathGenerator\SmartPathsGenerator;

class SmartPathsGeneratorTest extends \Tests\Unit\TestCase
{
    /**
     * @covers SmartPathsGenerator::getForDate()
     */
    public function testForOldDate()
    {
        $generator = new SmartPathsGenerator([
            new NewPathGenerator(),
            new OldPathGenerator(),
        ]);

        $date = (new \Carbon\Carbon())
            ->year(2017)
            ->month(4)
            ->day(25)
            ->hour(10)
            ->minute(30);

        $results = $generator->getForDate($date);

        $this->assertCount(1, $results);
        $this->assertEquals('/ELECTRO_L_2/2017/April/25_04_2017/25042017_10 30.jpg', $results[0]);
    }

    /**
     * @covers SmartPathsGenerator::getForDate()
     */
    public function testForNewDate()
    {
        $generator = new SmartPathsGenerator([
            new NewPathGenerator(),
            new OldPathGenerator(),
        ]);

        $date = (new \Carbon\Carbon())
            ->year(2017)
            ->month(8)
            ->day(11)
            ->hour(10)
            ->minute(30);

        $results = $generator->getForDate($date);

        $this->assertCount(1, $results);
        $this->assertEquals('/ELECTRO_L_2/2017/August/11/1030/170811_1030_RGB.jpg', $results[0]);
    }
}
