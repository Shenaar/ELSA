<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage\Commands;

use App\Service\PhotoStorage\SmartPhotoStorage\SmartPhotoStorage;

use Carbon\Carbon;

use Illuminate\Contracts\Filesystem\Filesystem;

use Symfony\Component\Console\Input\InputArgument;

/**
 * Dumps the cache of missing photos to a file.
 */
class DumpCommand extends SmartStorageCommand
{
    /**
     * @var string
     */
    protected $signature = 'smart:dump';

    /**
     * DumpCommand constructor.
     *
     * @param SmartPhotoStorage $storage
     * @param Filesystem $filesystem
     */
    public function __construct(SmartPhotoStorage $storage, Filesystem $filesystem)
    {
        parent::__construct($storage, $filesystem);

        $defaultFilename = './' . Carbon::now()->format('Y.m.d H:i') . '.dump';
        $this->addArgument('filename', InputArgument::OPTIONAL, '', $defaultFilename);
    }

    /**
     *
     */
    public function handle()
    {
        $filename = $this->argument('filename');

        $this->filesystem->put($filename, $this->storage->getCache()->implode(PHP_EOL));

        $this->output->success($this->storage->getCache()->count() . ' records dumped.');
    }
}
