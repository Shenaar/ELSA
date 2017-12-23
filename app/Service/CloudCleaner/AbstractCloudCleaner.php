<?php

namespace App\Service\CloudCleaner;

use App\Service\CloudCleaner\Contracts\CloudCleaner;
use App\Service\CloudCleaner\Contracts\ColorMapper;

/**
 * Can map colors for a photo.
 */
abstract class AbstractCloudCleaner implements CloudCleaner
{
    /**
     * @var ColorMapper
     */
    protected $mapper;

    /**
     * @param ColorMapper $mapper
     */
    public function __construct(ColorMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}
