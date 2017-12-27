<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage;

use App\Service\Dumper\Contracts\CanRestore;
use App\Service\Dumper\Exceptions\NothingToRestoreException;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 *
 */
class Restorer implements CanRestore
{
    /**
     * @var SmartPhotoStorage
     */
    protected $storage;

    /**
     * @param SmartPhotoStorage $storage
     */
    public function __construct(SmartPhotoStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     */
    public function restore(Filesystem $filesystem, string $directory)
    {
        $filename = $directory . '/smart/dates.dump';

        if (!$filesystem->exists($filename)) {
            throw new NothingToRestoreException();
        }

        $lines = collect(explode(PHP_EOL, $filesystem->get($filename)));
        $this->storage->setCache($lines);
    }
}
