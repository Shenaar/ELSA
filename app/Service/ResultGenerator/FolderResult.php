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
     * @param Filesystem $fileManager
     */
    public function __construct(Filesystem $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->photos = new Collection();
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        $this->photos->push($photo);
    }

    /**
     * @inheritdoc
     */
    public function store(string $path)
    {
        \File::deleteDirectory($path);
        \File::makeDirectory($path);

        $this->photos->each(function (Photo $photo) use ($path) {
            \File::put(
                $path . '/' . $photo->getDate()->toDateTimeString() . '.jpg',
                $photo->getData()
            );
        });
    }
}
