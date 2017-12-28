<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunCloudsMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clouds:run {date} {time} {step=0.1} {start=0.0} {end=1.0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $step = $this->argument('step');
        $start = $this->argument('start');
        $end = $this->argument('end');

        for ($treshold = $start; $treshold <= $end; $treshold += $step) {
            $this->line(sprintf('Running with the treshold (%.2f)', $treshold));

            Artisan::call('clouds:map', [
                'date' => $this->argument('date'),
                'time' => $this->argument('time'),
                'treshold' => $treshold
            ], $this->output);
        }
    }
}
