<?php

namespace App\Console\Commands;

use App\Service\Reporter\StatusReporter;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

class Monitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor {--delay=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitors the current status of the application';

    /**
     * StatusReport constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Application $app
     */
    public function handle(Application $app)
    {
        $reporters = collect($app->tagged('reports'));

        for (;;) {
            $report = [];

            try {
                $reporters->each(function (StatusReporter $item) use (&$report) {
                    $result = $item->getReport();
                    $report[] = [$result->getKey(), $result->getValue()];
                });
            } catch (\Exception $ex) {
                continue;
            }

            $this->output->writeln(Carbon::now()->toDateTimeString());
            $this->output->table(['Name', 'Value'], array_values($report));
            $this->output->write("\e[" . (count($report) + 1 + 4 + 1) . "A");

            sleep($this->option('delay'));
        }

    }
}
