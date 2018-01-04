<?php

namespace App\Console\Commands\Dumper;

use App\Service\Dumper\Contracts\CanDump;
use App\Service\Dumper\Exceptions\NothingToDumpException;

use Carbon\Carbon;

use Chumper\Zipper\Zipper;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter as Filesystem;
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
     * @var Zipper
     */
    private $zipper;

    /**
     * @param Filesystem $filesystem
     * @param Zipper $zipper
     */
    public function __construct(Filesystem $filesystem, Zipper $zipper)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->zipper = $zipper;
    }

    /**
     * @param Application $app
     *
     * @throws \Exception
     */
    public function handle(Application $app)
    {
        $startTime = microtime(true);
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

        $this->output->writeln('Zipping...');

        $zip = $this->zipper->make($this->filesystem->path($this->directory) . '.zip');
        $zip->add($this->filesystem->path($this->directory));
        $zip->close();

        $this->output->writeln('Dump is written in ' . $this->filesystem->path($this->directory) . '.zip');
        $this->output->success(sprintf('Finished in %.2fs', (microtime(true) - $startTime)));
    }
}
