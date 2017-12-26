<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunFullEarth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for ($black = 0.6; $black <= 1.0; $black += 0.1) {
            $white = 1.0 - $black;

            $this->line(sprintf('Running for the pair (%s, %s)', $black, $white));

            Artisan::call('earth:generate', ['blackWeight' => $black, 'whiteWeight' => $white], $this->output);
        }
    }
}
