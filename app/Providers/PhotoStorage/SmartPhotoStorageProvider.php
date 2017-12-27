<?php

namespace App\Providers\PhotoStorage;

use App\Service\PhotoStorage\Contracts\PhotoStorage;
use App\Service\PhotoStorage\SmartPhotoStorage\Commands\DumpCommand;
use App\Service\PhotoStorage\SmartPhotoStorage\Commands\RestoreCommand;
use App\Service\PhotoStorage\SmartPhotoStorage\Dumper;
use App\Service\PhotoStorage\SmartPhotoStorage\MissingCacheCountReport;
use App\Service\PhotoStorage\SmartPhotoStorage\Restorer;
use App\Service\PhotoStorage\SmartPhotoStorage\SmartPhotoStorage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class SmartPhotoStorageProvider extends ServiceProvider
{
    const DUMP_STORAGE = 'smart_dump';

    /**
     *
     */
    public function boot()
    {
        $old = $this->app->make(PhotoStorage::class);
        $this->app
            ->when(SmartPhotoStorage::class)
            ->needs(PhotoStorage::class)
            ->give(function () use ($old) {

                return $old;
            });

        $this->app->bind(PhotoStorage::class, SmartPhotoStorage::class);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function register()
    {
        $this->app->tag([MissingCacheCountReport::class], 'reports');

        /** @var FilesystemManager $fsManager */
        $fsManager = $this->app->get(FilesystemManager::class);

        $this->app->tag(Dumper::class, 'dumper');
        $this->app->tag(Restorer::class, 'restorer');
    }
}
