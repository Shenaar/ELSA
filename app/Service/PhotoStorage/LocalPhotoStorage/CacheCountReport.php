<?php

namespace App\Service\PhotoStorage\LocalPhotoStorage;

use App\Service\Reporter\Report;
use App\Service\Reporter\StatusReporter;

/**
 * Counts the cached photos.
 */
class CacheCountReport implements StatusReporter
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
        return new Report(
            'Cached Photos Count',
            count($this->storage->getFileSystem()->allFiles())
        );
    }

}
