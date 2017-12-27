<?php

namespace App\Console\Commands\Dumper;

use App\Service\Dumper\Contracts\CanDump;
use App\Service\Dumper\Exceptions\NothingToDumpException;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;

class DumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dumps the data.';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $directory;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * @param Application $app
     */
    public function handle(Application $app)
    {
        $this->directory = Carbon::now()->format('Y.m.d H:i');
        $this->filesystem->makeDirectory($this->directory);
        $dumpers = collect($app->tagged('dumper'));

        $dumpers->each(function (CanDump $item) {
            $this->output->writeln('Started dumping for ' . get_class($item));

            try {
                $item->dump($this->filesystem, $this->directory);
            } catch (NothingToDumpException $e) {
                $this->output->note('Nothing to dump here');

                return;
            }

            $this->output->writeln('Finished dumping');
        });
    }
}
