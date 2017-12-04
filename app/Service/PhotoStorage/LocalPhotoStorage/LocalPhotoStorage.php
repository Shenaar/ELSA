<?php

namespace App\Service\PhotoStorage\LocalPhotoStorage;

use App\Exceptions\PhotoNotFoundException;
use App\Service\Photo;
use App\Service\PhotoStorage\Contracts\PhotoStorage;

use Carbon\Carbon;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 *
 */
class LocalPhotoStorage implements PhotoStorage
{
    /**
     * @var PhotoStorage
     */
    private $photoDownloader;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * LocalPhotoStorage constructor.
     *
     * @param PhotoStorage $photoDownloader
     * @param Filesystem $fileSystem
     */
    public function __construct(PhotoStorage $photoDownloader, Filesystem $fileSystem)
    {
        $this->photoDownloader = $photoDownloader;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @inheritdoc
     */
    public function getForDate(Carbon $date)
    {
        $path = $this->generatePath($date);
        try {
            $photo = new Photo($date, $this->fileSystem->get($path));

            return $photo;
        } catch (FileNotFoundException $exception) {

        }

        $photo = $this->download($date);
        $this->fileSystem->put($path, $photo->getData());

        return $photo;
    }

    /**
     * @return Filesystem
     */
    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * @param Carbon $date
     *
     * @return Photo
     *
     * @throws PhotoNotFoundException
     */
    protected function download(Carbon $date)
    {
        $result = $this->photoDownloader->getForDate($date);

        return $result;
    }

    /**
     * @param Carbon $date
     *
     * @return string
     */
    protected function generatePath(Carbon $date)
    {
        return $date->format('/Y/m/d/ymd_Hi_\.\j\p\g');
    }
}
