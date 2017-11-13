<?php

namespace App\Service\PhotoStorage;

use App\Exceptions\PhotoNotFoundException;
use App\Service\PathGenerator\Contracts\PathsGenerator;
use App\Service\Photo;
use App\Service\PhotoStorage\Contracts\PhotoStorage;

use Carbon\Carbon;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Downloads the photo from a storage.
 */
class CheckFilesPhotoStorage implements PhotoStorage
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var PathsGenerator
     */
    private $pathsGenerator;

    /**
     * FtpPhotoDownloader constructor.
     *
     * @param Filesystem $fileSystem
     * @param PathsGenerator $pathsGenerator
     */
    public function __construct(Filesystem $fileSystem, PathsGenerator $pathsGenerator)
    {
        $this->fileSystem = $fileSystem;
        $this->pathsGenerator = $pathsGenerator;
    }

    /**
     * @inheritdoc
     */
    public function getForDate(Carbon $date)
    {
        foreach ($this->pathsGenerator->getForDate($date) as $path) {
            try {
                if ($this->fileSystem->exists($path)) {
                    return new Photo($date, null);
                }
            } catch (FileNotFoundException $exception) {

            }
        }

        throw new PhotoNotFoundException($date);
    }

}
