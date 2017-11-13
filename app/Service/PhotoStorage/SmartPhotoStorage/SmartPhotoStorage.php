<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage;

use App\Exceptions\PhotoNotFoundException;
use App\Service\PhotoStorage\Contracts\PhotoStorage;

use Carbon\Carbon;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;

/**
 *
 */
class SmartPhotoStorage implements PhotoStorage
{
    /**
     * @var PhotoStorage
     */
    private $storage;

    /**
     * @var Repository
     */
    private $cacheRepository;

    /**
     * @var Collection
     */
    private $cache;

    /**
     * SmartPhotoStorage constructor.
     *
     * @param PhotoStorage $storage
     * @param Repository $cacheRepository
     */
    public function __construct(PhotoStorage $storage, Repository $cacheRepository)
    {
        $this->storage = $storage;
        $this->cacheRepository = $cacheRepository;
        $this->cache = $cacheRepository->get(__CLASS__ . '.missing_photos', new Collection());
    }

    /**
     * @inheritdoc
     */
    public function getForDate(Carbon $date)
    {
        if ($this->isMissing($date)) {
            throw new PhotoNotFoundException($date);
        }

        try {
            return $this->storage->getForDate($date);
        } catch (PhotoNotFoundException $exception) {

            if ($date->isPast()) {
                $this->cache->push($date->toDateTimeString());
                $this->cacheRepository->forever(__CLASS__ . '.missing_photos', $this->cache);
            }

            throw $exception;
        }
    }

    /**
     * @param Carbon $date
     * @return bool
     */
    private function isMissing(Carbon $date)
    {
        $hour = $date->hour;
        $minute = $date->minute;

        return ($hour === 8 && $minute === 30) || ($hour === 9) || ($hour === 10 && $minute === 0) ||
            $this->cache->search($date->toDateTimeString());
    }

    /**
     * @param bool $reread
     *
     * @return Collection
     */
    public function getCache($reread = false)
    {
        if ($reread) {
            $this->cache = $this->cacheRepository->get(__CLASS__ . '.missing_photos', new Collection());
        }

        return $this->cache;
    }
}
