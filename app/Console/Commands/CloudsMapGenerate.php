<?php

namespace App\Console\Commands;

use App\Service\ColorMapper\Contracts\ColorMapper;
use App\Service\PhotoProcessor\CachingResize;
use App\Service\PhotoProcessor\FilterEmpty;
use App\Service\ResultGenerator\CloudsMapResult;

class CloudsMapGenerate extends DownloadDayTime
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clouds:map {date} {time} {treshold}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a clouds map for the {date} {time} using the {treshold}.';

    /**
     * @inheritdoc
     */
    protected function getResultGenerator()
    {
        return new CloudsMapResult(
            app(ColorMapper::class),
            $this->argument('treshold')
        );
    }

    /**
     * @inheritdoc
     */
    protected function getProcessors()
    {
        return [
            app(FilterEmpty::class),
        ];
    }
}
