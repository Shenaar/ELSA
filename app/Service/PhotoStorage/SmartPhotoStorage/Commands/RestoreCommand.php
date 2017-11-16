<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage\Commands;

use App\Service\PhotoStorage\SmartPhotoStorage\SmartPhotoStorage;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Restores the cache of missing photos from a file.
 */
class RestoreCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'smart:restore {filename}';

    /**
     * @var SmartPhotoStorage
     */
    private $storage;

    /**
     * DumpCommand constructor.
     * @param SmartPhotoStorage $storage
     */
    public function __construct(SmartPhotoStorage $storage)
    {
        parent::__construct();

        $this->storage = $storage;
    }

    /**
     * @param Filesystem $fs
     */
    public function handle(Filesystem $fs)
    {
        $filename = $this->argument('filename');

        if (!$fs->isReadable($filename)) {
            $this->output->warning('File is not readable, exiting');

            return;
        }

        $lines = collect(explode(PHP_EOL, $fs->get($filename)));
        $this->storage->setCache($lines);

        $this->output->success($lines->count() . ' records restored.');
    }
}
