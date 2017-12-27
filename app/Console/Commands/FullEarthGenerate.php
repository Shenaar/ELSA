<?php

namespace App\Console\Commands;

use App\Service\CloudCleaner\LeastWhiteCloudCleaner;
use App\Service\ColorMapper\Contracts\ColorMapper;
use App\Service\PhotoProcessor\FilterEmpty;
use App\Service\PhotoProcessor\CachingResize;
use App\Service\ResultGenerator\FullEarthResult;

use Carbon\Carbon;

use Illuminate\Support\Collection;

class FullEarthGenerate extends DownloadDaysTime
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'earth:generate {blackWeight} {whiteWeight}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an image of the Earth without clouds.';

    /**
     * @inheritdoc
     */
    protected function getDatesList()
    {
        $startDate = (new Carbon('24.02.2017'))->setTime(10, 30, 0);
        $endDate = (new Carbon('today'))->setTime(10, 30, 59);

        return $this->generateRange($startDate, $endDate);
    }

    /**
     * @inheritdoc
     */
    protected function getResultGenerator()
    {
        return new FullEarthResult(
            new LeastWhiteCloudCleaner(
                app(ColorMapper::class),
                $this->argument('blackWeight'),
                $this->argument('whiteWeight')
            )
        );
    }

    /**
     * @inheritdoc
     */
    protected function getProcessors()
    {
        return [
            app(FilterEmpty::class),
            app(CachingResize::class),
        ];
    }


    /**
     * @inheritdoc
     */
    protected function generateRange(Carbon $startDate, Carbon $endDate)
    {
        $result = new Collection();
        while ($startDate->lessThan($endDate)) {
            $result->push(clone $startDate);
            $startDate->addDay();
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function getFilename()
    {
        return sprintf('Earth %.1f %.1f', $this->argument('blackWeight'), $this->argument('whiteWeight'));
    }
}
