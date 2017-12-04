<?php

namespace App\Service\ResultGenerator;

use App\Service\Photo;
use App\Service\ResultGenerator\Contracts\ResultGenerator;

/**
 * Generates a GIF using the photos.
 */
class GifResult implements ResultGenerator
{
    /**
     * @var \Imagick
     */
    private $gif;

    /**
     * @var int
     */
    private $count;

    /**
     * GifResult constructor.
     */
    public function __construct()
    {
        $this->gif = new \Imagick();
        $this->gif->setFormat('gif');
        $this->count = 0;
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        $frame = new \Imagick();
        $frame->readImageBlob($photo->getData());
        $frame->setImageDelay(10);

        $this->gif->addImage($frame);
        ++$this->count;
    }

    /**
     * @inheritdoc
     */
    public function store(string $path)
    {
        if ($this->count === 0) {
            return;
        }

        $this->gif->writeImages($path . '.gif', true);
    }

}
