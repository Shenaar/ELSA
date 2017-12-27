<?php

namespace App\Providers;

use App\Service\PhotoProcessor\CachingResize;
use App\Service\PhotoProcessor\Resize;
use App\Service\PhotoProcessor\Timestamp;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class PhotoProcessorProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        $this->app->bind(Timestamp::class, function (Application $app) use ($config) {

            return new Timestamp(
                $config->get('process.timestamp.x'), $config->get('process.timestamp.y'),
                $config->get('process.timestamp.color'), $config->get('process.timestamp.fontSize')
            );
        });

        $this->app->bind(Resize::class, function (Application $app) use ($config) {

            return new Resize(
                $config->get('process.resize.width'),
                $config->get('process.resize.height')
            );
        });

        $this->app->bind(CachingResize::class, function (Application $app) use ($config) {
            /** @var FilesystemManager $fsManager */
            $fsManager = $app->get(FilesystemManager::class);

            return new CachingResize(
                $config->get('process.resize.width'),
                $config->get('process.resize.height'),
                $fsManager->disk('caching_resize')
            );
        });
    }
}
