<?php

namespace App\Service\PhotoProcessor;

use App\Service\Photo;
use App\Service\PhotoProcessor\Contracts\PhotoProcessor;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Resizes a photo.
 */
class CachingResize extends Resize
{
    /**
     * @var Filesystem
     */
    private $storage;

    /**
     * @param int $width
     * @param int $height
     * @param Filesystem $storage
     */
    public function __construct(int $width, int $height, Filesystem $storage)
    {
        parent::__construct($width, $height);

        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     */
    public function process(Photo $photo)
    {
        try {
            $content = $this->storage->get($this->generatePath($photo));
            $photo->setData($content);
        } catch (FileNotFoundException $exception) {
            $photo = parent::process($photo);
            $this->storage->put($this->generatePath($photo), $photo->getData());
        }

        return $photo;
    }

    /**
     * @param Photo $photo
     *
     * @return string
     */
    protected function generatePath(Photo $photo)
    {
        return '/' . $this->width . '-' . $this->height .
            ($photo
            ->getDate()
            ->format('/Y/m/d/ymd_Hi_\.\j\p\g'));
    }
}
