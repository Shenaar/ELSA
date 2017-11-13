<?php

namespace App\Providers\PhotoStorage;

use App\Service\PhotoStorage\Contracts\PhotoStorage;
use App\Service\PhotoStorage\LocalPhotoStorage\CacheCountReport;
use App\Service\PhotoStorage\LocalPhotoStorage\CacheSizeReport;
use App\Service\PhotoStorage\LocalPhotoStorage\ClearCacheCommand;
use App\Service\PhotoStorage\LocalPhotoStorage\LocalPhotoStorage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;


/**
 *
 */
class LocalPhotoStorageProvider extends ServiceProvider
{
    const LOCAL_STORAGE = 'local_photo_storage';

    public function boot()
    {
        /** @var PhotoStorage $old */
        $old = $this->app->make(PhotoStorage::class);

        $this->app
            ->when(LocalPhotoStorage::class)
            ->needs(PhotoStorage::class)
            ->give(function () use ($old) {
                return $old;
            });

        $this->app->bind(PhotoStorage::class, LocalPhotoStorage::class);
    }

    public function register()
    {
        /** @var FilesystemManager $fsManager */
        $fsManager = $this->app->get(FilesystemManager::class);

        $this->app
            ->when(LocalPhotoStorage::class)
            ->needs(Filesystem::class)
            ->give(function () use ($fsManager) {
                return $fsManager->disk(self::LOCAL_STORAGE);
            });

        $this->app->tag([CacheCountReport::class, CacheSizeReport::class], 'reports');

        $this->commands(ClearCacheCommand::class);
    }
}
