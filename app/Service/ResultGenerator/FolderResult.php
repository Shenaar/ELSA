<?php

namespace App\Service\ResultGenerator;

use App\Service\Photo;
use App\Service\ResultGenerator\Contracts\ResultGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

/**
 * Stores photos in a folder.
 */
class FolderResult implements ResultGenerator
{
    /**
     * @var Filesystem
     */
    private $fileManager;

    /**
     * @var Collection
     */
    private $photos;

    /**
     * @var string
     */
    private $path;

    /**
     * @param Filesystem $fileManager
     */
    public function __construct(Filesystem $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->photos = new Collection();
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        if (!\File::exists($this->path)) {
            \File::makeDirectory($this->path);
        }

        \File::put(
            $this->path . '/' . $photo->getDate()->toDateTimeString() . '.jpg',
            $photo->getData()
        );
    }

    /**
     * @inheritdoc
     */
    public function store(string $path)
    {
        return;
    }
}
