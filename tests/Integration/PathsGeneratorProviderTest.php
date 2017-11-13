<?php

namespace Tests\Integration;

use App\Providers\PathsGeneratorProvider;
use App\Service\PathGenerator\Contracts\PathsGenerator;

use Tests\TestCase;

class PathsGeneratorProviderTest extends TestCase
{
    /**
     * @covers PathsGeneratorProvider::register()
     */
    public function testBinding()
    {
        $generator = $this->app->make(PathsGenerator::class);

        $this->assertInstanceOf(PathsGenerator::class, $generator);
    }

    /**
     *
     */
    public function testGenerating()
    {
        $generator = $this->app->make(PathsGenerator::class);

        $date = (new \Carbon\Carbon())
            ->year(2017)
            ->month(8)
            ->day(11)
            ->hour(10)
            ->minute(30);

        $result = $generator->getForDate($date);

        $this->assertNotEmpty($result);
    }
}
