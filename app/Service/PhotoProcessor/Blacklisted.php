<?php

namespace App\Service\PhotoProcessor;

use App\Service\Photo;
use App\Service\PhotoProcessor\Contracts\PhotoProcessor;

use Illuminate\Support\Collection;

class Blacklisted implements PhotoProcessor
{
    /** @var  Collection */
    private $blacklist;

    /**
     * @param Collection $blacklist
     */
    public function __construct(Collection $blacklist)
    {
        $this->blacklist = $blacklist;
    }

    /**
     * @inheritdoc
     */
    public function process(Photo $photo)
    {
        if ($this->blacklist->search($photo->getDate()) === false) {
            return $photo;
        }

        return null;
    }
}
