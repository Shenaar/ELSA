<?php

namespace App\Console\Commands\Dumper;

use App\Service\Dumper\Contracts\CanRestore;
use App\Service\Dumper\Exceptions\NothingToRestoreException;

use Chumper\Zipper\Zipper;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;

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
     * @var FilesystemAdapter
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
     * @param FilesystemAdapter $filesystem
     * @param Zipper $zipper
     */
    public function __construct(FilesystemAdapter $filesystem, Zipper $zipper)
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

        $file = $this->getLastDumpFile();
        if (!$file) {
            $this->error('No dumps at all, exiting.');

            return;
        }

        $this->extract($file);

        $this->restore();

        $this->cleanup();

        $this->output->success(sprintf('Finished in %.2fs', (microtime(true) - $startTime)));
    }

    /**
     * @param string $file
     *
     * @throws \Exception
     */
    protected function extract(string $file)
    {
        $this->output->writeln('Restoring from ' . $file);

        $this->directory = substr($file, 0, -4);
        $this->filesystem->deleteDirectory($this->directory);

        $zip = $this->zipper->make($this->filesystem->path($file));
        $zip->extractTo($this->filesystem->path($this->directory));
    }

    /*
     * Iterates over restorers and runs them.
     */
    protected function restore()
    {
        $restorers = collect($this->getLaravel()->tagged('restorer'));

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
     * Cleans up temporary files.
     */
    protected function cleanup()
    {
        $this->output->writeln('Cleaning up...');
        $this->filesystem->deleteDirectory($this->directory);
    }

    /**
     * @return string
     */
    private function getLastDumpFile()
    {
        $file =
            collect($this->filesystem->files())
                ->filter(function (string $filename) {
                    return ends_with($filename, '.zip');
                })
                ->sort()
                ->last();

        return $file;
    }
}
