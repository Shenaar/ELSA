<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Support\Collection;

class DownloadFullDays extends AbstractDownloadCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:days {startDate} {endDate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads all photos between {startDate} and {endDate}';

    /**
     * @inheritdoc
     */
    protected function getDatesList()
    {
        $startDate = (new Carbon($this->argument('startDate')))->setTime(0, 0, 0);
        $endDate = (new Carbon($this->argument('endDate')))->setTime(23, 59, 59);

        $result = new Collection();
        while($startDate->lessThan($endDate)) {
            $result->push(clone $startDate);
            $startDate->addMinutes(30);
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

        return $startDate->format('d.m.Y') . '-' . $endDate->format('d.m.Y');
    }
}
