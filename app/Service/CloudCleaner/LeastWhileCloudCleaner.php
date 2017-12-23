<?php

namespace App\Service\CloudCleaner;
use App\Service\CloudCleaner\Contracts\ColorMapper;

/**
 * Chooses the least white pixel.
 */
class LeastWhileCloudCleaner extends AbstractCloudCleaner
{
    /**
     * @var
     */
    private $map;

    /**
     * @param ColorMapper $colorMapper
     */
    public function __construct(ColorMapper $colorMapper)
    {
        parent::__construct($colorMapper);
    }
}
