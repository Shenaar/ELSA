<?php

namespace App\Providers\PhotoStorage;

use App\Service\PhotoStorage\CheckFilesPhotoStorage;
use App\Service\PhotoStorage\Contracts\PhotoStorage;
use App\Service\PhotoStorage\FilesystemPhotoStorage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class PhotoStorageProvider extends ServiceProvider
{
    const ELECTRO_L2_FTP = 'electro_l_2_ftp';

    /**
     *
     */
    public function register()
    {
        /** @var FilesystemManager $fsManager */
        $fsManager = $this->app->get(FilesystemManager::class);

        $this->app
            ->when(FilesystemPhotoStorage::class)
            ->needs(Filesystem::class)
            ->give(function () use ($fsManager) {
                return $fsManager->disk(self::ELECTRO_L2_FTP);
            });

        $this->app->bind(PhotoStorage::class, FilesystemPhotoStorage::class);
    }
}
