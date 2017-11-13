<?php

namespace App\Service\Reporter;

interface StatusReporter
{
    /**
     * @return Report
     */
    public function getReport();
}
