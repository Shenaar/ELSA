<?php

namespace Tests\Integration;

use App\Exceptions\PhotoNotFoundException;
use App\Service\PhotoStorage\Contracts\PhotoStorage;

use Tests\TestCase;

class PhotoDownloaderProviderTest extends TestCase
{
    /**
     * @covers PhotoStorageProvider::register()
     */
    public function testBinding()
    {
        $downloader = $this->app->make(PhotoStorage::class);

        $this->assertInstanceOf(PhotoStorage::class, $downloader);
    }

    /**
     *
     */
    public function testDownload()
    {
        /** @var PhotoStorage $downloader */
        $downloader = $this->app->make(PhotoStorage::class);

        $date = (new \Carbon\Carbon())
            ->year(2017)
            ->month(8)
            ->day(11)
            ->hour(10)
            ->minute(30);

        $res = $downloader->getForDate($date);

        $this->assertNotNull($res);
    }

    /**
     *
     */
    public function testNotFound()
    {
        /** @var PhotoStorage $downloader */
        $downloader = $this->app->make(PhotoStorage::class);

        $date = (new \Carbon\Carbon())
            ->year(1990)
            ->month(8)
            ->day(11)
            ->hour(10)
            ->minute(30);

        $this->expectException(PhotoNotFoundException::class);

        $downloader->getForDate($date);
    }
}
