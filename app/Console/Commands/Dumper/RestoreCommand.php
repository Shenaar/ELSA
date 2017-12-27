<?php

namespace App\Console\Commands\Dumper;

use App\Service\Dumper\Contracts\CanDump;
use App\Service\Dumper\Contracts\CanRestore;
use App\Service\Dumper\Exceptions\NothingToDumpException;
use App\Service\Dumper\Exceptions\NothingToRestoreException;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;

class RestoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restores the data.';

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
        $this->directory = $this->getLastDumpDirectory();

        $restorers = collect($app->tagged('restorer'));

        $restorers->each(function (CanRestore $item) {
            $this->output->writeln('Started restoring for ' . get_class($item));

            try {
                $item->restore($this->filesystem, $this->directory);
            } catch (NothingToRestoreException $e) {
                $this->output->note('Nothing to restore here');

                return;
            }

            $this->output->writeln('Finished restoring');
        });
    }

    /**
     * @return string
     */
    private function getLastDumpDirectory()
    {
        $directory =
            collect($this->filesystem->directories())
                ->sort()
                ->last();

        return $directory;
    }
}
