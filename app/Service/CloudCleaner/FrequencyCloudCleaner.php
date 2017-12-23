<?php

namespace App\Service\CloudCleaner;

use App\Service\CloudCleaner\Contracts\ColorMapper;

/**
 * Removes clouds using the most frequent color for a pixel.
 */
class FrequencyCloudCleaner extends AbstractCloudCleaner
{
    /**
     * @param ColorMapper $mapper
     */
    public function __construct(ColorMapper $mapper)
    {
        parent::__construct($mapper);
    }
}
