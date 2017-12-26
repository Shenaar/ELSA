<?php

namespace App\Service\CloudCleaner;

use App\Service\Photo;

use Illuminate\Contracts\Cache\Repository;
use \Imagick;

/**
 * Caches a photo mapping.
 */
class CachingColorMapper extends SimpleColorMapper
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * CachingSimpleColorMapper constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function map(Photo $photo)
    {
        $imagick = new Imagick();
        $imagick->readImageBlob($photo->getData());
        $size = $imagick->getImageGeometry();
        $key = __CLASS__ . '.cache.' . $photo->getDate()->toDateTimeString(). '.' . implode($size, '.');

        $imagick->destroy();

        $cached = $this->repository->get($key);

        if (!$cached) {
            $cached = parent::map($photo);

            $this->repository->forever($key, $cached);
        }

        return $cached;
    }
}
