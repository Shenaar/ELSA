<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Support\Collection;

class DownloadDaysTime extends AbstractDownloadCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:daysTime {startDate} {endDate} {time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads photos for {time} for every day between {startDate} and {endDate}';

    /**
     * @inheritdoc
     */
    protected function getDatesList()
    {
        list($hours, $minutes) = explode(':', $this->argument('time'));
        $startDate = (new Carbon($this->argument('startDate')))->setTime($hours, $minutes, 0);
        $endDate = (new Carbon($this->argument('endDate')))->setTime($hours, $minutes, 59);

        return $this->generateRange($startDate, $endDate);
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @return Collection
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
        $startDate = (new Carbon($this->argument('startDate')))->setTime(0, 0, 0);
        $endDate = (new Carbon($this->argument('endDate')))->setTime(23, 59, 59);

        return $startDate->format('d.m.Y') . '-' . $endDate->format('d.m.Y') . ' ' . $this->argument('time');
    }
}
