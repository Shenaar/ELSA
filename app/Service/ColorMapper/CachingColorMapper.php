<?php

namespace App\Service\ColorMapper;

use App\Service\Color;
use App\Service\Photo;

use Illuminate\Contracts\Cache\Repository;

use Illuminate\Support\Collection;
use \Imagick;

/**
 * Caches a photo mapping.
 */
class CachingColorMapper extends SimpleColorMapper
{
    const KEYS_KEY = __CLASS__ . '.cache.keys';
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var Collection
     */
    private $cachedKeys;

    /**
     * CachingSimpleColorMapper constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->cachedKeys = $this->repository->get(self::KEYS_KEY, new Collection());
    }

    /**
     * @inheritdoc
     */
    public function map(Photo $photo)
    {
        $imagick = new Imagick();
        $imagick->readImageBlob($photo->getData());
        $size = $imagick->getImageGeometry();
        $key = $photo->getDate()->toDateTimeString(). '.' . implode($size, '.');

        $imagick->destroy();

        $cached = $this->repository->get(__CLASS__ . '.cache.' . $key);

        if (!$cached) {
            $result = parent::map($photo);

            $this->setForKey($key, $this->toHex($result));

            return $result;
        }

        return $this->toColor($cached);
    }

    /**
     * @return int
     */
    public function getCacheSize()
    {
        return $this->getCachedKeys(true)->count();
    }

    /**
     * @param bool $reread
     *
     * @return Collection
     */
    public function getCachedKeys($reread = false)
    {
        if ($reread) {
            $this->cachedKeys = $this->repository->get(self::KEYS_KEY, new Collection());
        }

        return $this->cachedKeys;
    }

    /**
     * @param Collection $cachedKeys
     *
     * @return CachingColorMapper
     */
    public function setCachedKeys(Collection $cachedKeys)
    {
        $this->cachedKeys = $cachedKeys;
        $this->repository->forever(self::KEYS_KEY, $this->cachedKeys);

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string[][]
     */
    public function getForKey(string $key)
    {
        return $this->repository->get(__CLASS__ . '.cache.' . $key);
    }

    /**
     * @param string     $key
     * @param string[][] $value
     */
    public function setForKey(string $key, array $value)
    {
        $this->repository->forever(__CLASS__ . '.cache.' . $key, $value);

        $this->cachedKeys->push($key);
        $this->repository->forever(self::KEYS_KEY, $this->cachedKeys);
    }

    /**
     *
     */
    public function clearCache()
    {
        $this->cachedKeys = $this->cachedKeys->filter(function (string $key) {
            $this->repository->forget(__CLASS__ . '.cache.' . $key);

            return false;
        });

        $this->repository->forever(self::KEYS_KEY, $this->cachedKeys);
    }

    /**
     * @param Color[][] $array
     *
     * @return string[][]
     */
    protected function toHex(array $array)
    {
        $result = array_map(function (array $row) {
            return array_map(function (Color $item) {
                return $item->toHexString();
            }, $row);
        }, $array);

        return $result;
    }

    /**
     * @param string[][] $array
     *
     * @return Color[][]
     */
    protected function toColor(array $array)
    {
        $result = array_map(function (array $row) {
            return array_map(function (string $item) {
                return Color::fromHex($item);
            }, $row);
        }, $array);

        return $result;
    }
}
