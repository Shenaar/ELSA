<?php

namespace App\Service\ColorMapper;

use App\Service\Reporter\Report;
use App\Service\Reporter\StatusReporter;

/**
 *
 */
class CachingColorMapperReporter implements StatusReporter
{
    /** @var CachingColorMapper */
    private $mapper;

    /**
     * @param CachingColorMapper $mapper
     */
    public function __construct(CachingColorMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @inheritdoc
     */
    public function getReport()
    {
        return new Report('Mapper Cache Count', $this->mapper->getCacheSize());
    }


}
