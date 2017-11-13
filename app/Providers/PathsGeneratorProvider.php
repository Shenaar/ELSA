<?php

namespace App\Providers;

use App\Service\PathGenerator\Contracts\PathsGenerator;
use App\Service\PathGenerator\Generators\NewPathGenerator;
use App\Service\PathGenerator\Generators\OldPathGenerator;
use App\Service\PathGenerator\SimplePathsGenerator;

use App\Service\PathGenerator\SmartPathsGenerator;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class PathsGeneratorProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        $this->app->tag([NewPathGenerator::class, OldPathGenerator::class], 'pathGenerators');

        $this->app->bind(PathsGenerator::class, function () {
            $generators = $this->app->tagged('pathGenerators');

            return new SmartPathsGenerator($generators);
        });
    }
}
