<?php

namespace App\Service\ResultGenerator;

use App\Service\Photo;
use App\Service\ResultGenerator\Contracts\ResultGenerator;

use Chumper\Zipper\Zipper;

use Illuminate\Support\Collection;

/**
 * Stores photos as a ZIP archive.
 */
class ZipResult implements ResultGenerator
{
    /**
     * @var Zipper
     */
    private $zipper;

    /**
     * @var Collection
     */
    private $files;

    /**
     * ZipResult constructor.
     *
     * @param Zipper $zipper
     */
    public function __construct(Zipper $zipper)
    {
        $this->zipper = $zipper;
        $this->files = new Collection();
    }

    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
        $this->files->put(
            $photo->getDate()->format('Ymd_Hi') . '.jpg',
            $photo->getData()
        );
    }

    /**
     * @inheritdoc
     */
    public function store($path)
    {
        $this->zipper->zip($path . '.zip');
        $this->zipper->folder('ELSA');

        $this->files->each(function ($photo, $filename) {
            $this->zipper->addString($filename, $photo);
        });

        $this->zipper->close();
    }
}
