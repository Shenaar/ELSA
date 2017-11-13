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
    protected $description = '';

    /**
     * @inheritdoc
     */
    protected function getDatesList()
    {
        list($hours, $minutes) = explode(':', $this->argument('time'));
        $startDate = (new Carbon($this->argument('startDate')))->setTime($hours, $minutes, 0);
        $endDate = (new Carbon($this->argument('endDate')))->setTime($hours, $minutes, 59);

        $result = new Collection();
        while($startDate->lessThan($endDate)) {
            $result->push(clone $startDate);
            $startDate->addDay();
        }

        return $result;
    }
}
