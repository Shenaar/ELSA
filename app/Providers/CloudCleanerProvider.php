<?php

namespace App\Providers;

use App\Service\CloudCleaner\CachingColorMapper;
use App\Service\CloudCleaner\Contracts\CloudCleaner;
use App\Service\CloudCleaner\Contracts\ColorMapper;
use App\Service\CloudCleaner\FrequencyCloudCleaner;
use App\Service\CloudCleaner\LeastWhileCloudCleaner;
use App\Service\CloudCleaner\SimpleColorMapper;

use Illuminate\Support\ServiceProvider;

class CloudCleanerProvider extends ServiceProvider
{
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
        $this->app->bind(ColorMapper::class, SimpleColorMapper::class);
        $this->app->bind(ColorMapper::class, CachingColorMapper::class);

        $this->app->bind(CloudCleaner::class, FrequencyCloudCleaner::class);
        $this->app->bind(CloudCleaner::class, LeastWhileCloudCleaner::class);
    }
}
