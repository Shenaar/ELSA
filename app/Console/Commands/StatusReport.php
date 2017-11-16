<?php

namespace App\Console\Commands;

use App\Service\Reporter\StatusReporter;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

class StatusReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reports the current status of the application';

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

        $report = [];

        $reporters->each(function (StatusReporter $item) use (&$report) {
            $result = $item->getReport();
            $report[] = [$result->getKey(), $result->getValue()];
        });

        $this->output->table(['Name', 'Value'], array_values($report));
    }
}
