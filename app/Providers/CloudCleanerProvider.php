<?php

namespace App\Providers;


use App\Service\CloudCleaner\Contracts\CloudCleaner;
use App\Service\CloudCleaner\FrequencyCloudCleaner;
use App\Service\CloudCleaner\LeastWhiteCloudCleaner;
use App\Service\ColorMapper\CachingColorMapper;
use App\Service\ColorMapper\CachingColorMapperReporter;
use App\Service\ColorMapper\Contracts\ColorMapper;
use App\Service\ColorMapper\Dumper;
use App\Service\ColorMapper\Restorer;
use App\Service\ColorMapper\SimpleColorMapper;

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
        $this->app->tag([CachingColorMapperReporter::class], 'reports');
        $this->app->tag(Dumper::class, 'dumper');
        $this->app->tag(Restorer::class, 'restorer');

        $this->app->bind(CloudCleaner::class, FrequencyCloudCleaner::class);
        $this->app->bind(CloudCleaner::class, LeastWhiteCloudCleaner::class);
    }
}
