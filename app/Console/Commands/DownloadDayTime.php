<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Support\Collection;

class DownloadDayTime extends AbstractDownloadCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:dayTime {date} {time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads the photo for {date}{time}';

    /**
     * @inheritdoc
     */
    protected function getDatesList()
    {
        list($hours, $minutes) = explode(':', $this->argument('time'));
        $date = (new Carbon($this->argument('date')))->setTime($hours, $minutes, 0);

        $result = new Collection();

        $result->push($date);

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function getFilename()
    {
        list($hours, $minutes) = explode(':', $this->argument('time'));
        $date = (new Carbon($this->argument('date')))->setTime($hours, $minutes, 0);

        return $date->format('d.m.Y H:i');
    }
}
