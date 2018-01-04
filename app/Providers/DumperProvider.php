<?php

namespace App\Providers;

use App\Console\Commands\Dumper\DumpCommand;
use App\Console\Commands\Dumper\RestoreCommand;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

class DumperProvider extends ServiceProvider
{
    const DUMP_STORAGE = 'dump_storage';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(DumpCommand::class)
            ->needs(FilesystemAdapter::class)
            ->give(function () {
                /** @var FilesystemManager $fsManager */
                $fsManager = $this->app->make(FilesystemManager::class);

                return $fsManager->disk(self::DUMP_STORAGE);
            });

        $this->app->when(RestoreCommand::class)
            ->needs(FilesystemAdapter::class)
            ->give(function () {
                /** @var FilesystemManager $fsManager */
                $fsManager = $this->app->make(FilesystemManager::class);

                return $fsManager->disk(self::DUMP_STORAGE);
            });
    }
}
