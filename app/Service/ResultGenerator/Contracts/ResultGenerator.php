<?php

namespace App\Service\ResultGenerator\Contracts;

use App\Service\Photo;

/**
 * Used to store photos.
 *
 * @package App\Service\ResultGenerator\Contracts
 */
interface ResultGenerator
{
    /**
     * @param Photo $photo
     *
     * @return mixed
     */
    public function addPhoto(Photo $photo);

    /**
     * @param string $path
     *
     * @return mixed
     */
    public function store($path);
}
