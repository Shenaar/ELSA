<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage;

use App\Service\Dumper\Contracts\CanDump;
use App\Service\Dumper\Exceptions\NothingToDumpException;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 *
 */
class Dumper implements CanDump
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
    public function dump(Filesystem $filesystem, string $directory)
    {
        $cache = $this->storage->getCache(true);

        if (count($cache) === 0) {
            throw new NothingToDumpException();
        }

        $path = $directory . '/smart';
        $filesystem->makeDirectory($path);

        $filesystem->put($path . '/dates.dump', $cache->implode(PHP_EOL));
    }
}
