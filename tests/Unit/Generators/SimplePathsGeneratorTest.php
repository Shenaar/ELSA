<?php

use App\Service\PathGenerator\Generators\NewPathGenerator;
use App\Service\PathGenerator\Generators\OldPathGenerator;
use App\Service\PathGenerator\SimplePathsGenerator;

class SimplePathsGeneratorTest extends \Tests\Unit\TestCase
{
    /**
     * @covers SimplePathsGenerator::getForDate()
     */
    public function testForDate()
    {
        $generator = new SimplePathsGenerator([
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
        $expected = [
            (new NewPathGenerator())->getForDate($date),
            (new OldPathGenerator())->getForDate($date),
        ];

        $this->assertArraySubset($expected, $results);
    }
}
