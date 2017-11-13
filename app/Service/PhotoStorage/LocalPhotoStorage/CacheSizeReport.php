<?php

namespace App\Service\PhotoStorage\LocalPhotoStorage;

use App\Service\Reporter\Report;
use App\Service\Reporter\StatusReporter;

/**
 * Counts the size of the cached photos.
 */
class CacheSizeReport implements StatusReporter
{
    /**
     * @var LocalPhotoStorage
     */
    private $storage;

    /**
     * CacheCountReport constructor.
     *
     * @param LocalPhotoStorage $storage
     */
    public function __construct(LocalPhotoStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     */
    public function getReport()
    {
        $files = collect($this->storage->getFileSystem()->allFiles());
        $result = 0;

        $files->each(function ($item) use (&$result) {
            $result += $this->storage->getFileSystem()->size($item);
        });

        return new Report(
            'Cache Photos Size',
            sprintf('%.2f Mb', round($result / 1024 / 1024, 2))
        );
    }

}
