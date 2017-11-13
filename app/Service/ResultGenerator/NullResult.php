<?php

namespace App\Service\ResultGenerator;

use App\Service\Photo;
use App\Service\ResultGenerator\Contracts\ResultGenerator;

/**
 * Does nothing.
 */
class NullResult implements ResultGenerator
{
    /**
     * @inheritdoc
     */
    public function addPhoto(Photo $photo)
    {
    }

    /**
     * @inheritdoc
     */
    public function store($path)
    {
    }

}
