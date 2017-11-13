<?php

namespace App\Providers\PhotoStorage;

use App\Service\PhotoStorage\Contracts\PhotoStorage;
use App\Service\PhotoStorage\SmartPhotoStorage\MissingCacheCountReport;
use App\Service\PhotoStorage\SmartPhotoStorage\SmartPhotoStorage;

use Illuminate\Support\ServiceProvider;

/**
 *
 */
class SmartPhotoStorageProvider extends ServiceProvider
{
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

        $this->app->tag([MissingCacheCountReport::class], 'reports');
    }
}
