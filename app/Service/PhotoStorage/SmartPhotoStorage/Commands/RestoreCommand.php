<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage\Commands;

use App\Service\PhotoStorage\SmartPhotoStorage\SmartPhotoStorage;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Restores the cache of missing photos from a file.
 */
class RestoreCommand extends SmartStorageCommand
{
    /**
     * @var string
     */
    protected $signature = 'smart:restore {filename?}';

    /**
     * RestoreCommand constructor.
     *
     * @param SmartPhotoStorage $storage
     * @param Filesystem $filesystem
     */
    public function __construct(SmartPhotoStorage $storage, Filesystem $filesystem)
    {
        parent::__construct($storage, $filesystem);
    }

    /**
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $filename = $this->argument('filename') ? : $this->getLastDumpFilename();

        if (!$this->filesystem->exists($filename)) {
            $this->output->warning('File is not readable, exiting');

            return;
        }

        $lines = collect(explode(PHP_EOL, $this->filesystem->get($filename)));
        $this->storage->setCache($lines);

        $this->output->success($lines->count() . ' records restored.');
    }

    /**
     * @return string
     */
    private function getLastDumpFilename()
    {
        $file =
            collect($this->filesystem->allFiles())
            ->filter(function (string $name) {
                return ends_with($name, '.dump');
            })
            ->sort()
            ->last();

        return $file;
    }
}
