<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage;
use App\Service\Reporter\Report;
use App\Service\Reporter\StatusReporter;

/**
 *
 */
class MissingCacheCountReport implements StatusReporter
{
    /**
     * @var SmartPhotoStorage
     */
    private $storage;

    /**
     * MissingCacheCountReport constructor.
     * @param SmartPhotoStorage $storage
     */
    public function __construct(SmartPhotoStorage $storage)
    {
        $this->storage = $storage;
    }


    public function getReport()
    {
        return new Report(
            'Missing Cache Count',
            $this->storage->getCache(true)->count()
        );
    }

}
