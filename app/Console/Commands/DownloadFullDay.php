<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Support\Collection;

class DownloadFullDay extends AbstractDownloadCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:day {date}';

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
        $date = new Carbon($this->argument('date'));

        $result = new Collection();
        for ($i = 0; $i < 47; ++$i) {
            $result->push(clone $date);
            $date->addMinutes(30);
        }

        return $result;
    }
}
