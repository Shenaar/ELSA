<?php

namespace App\Service\Dumper\Contracts;

use App\Service\Dumper\Exceptions\NothingToDumpException;

use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Represents something that can dump data.
 */
interface CanDump
{
    /**
     * @param Filesystem $filesystem
     * @param string $directory
     *
     * @throws NothingToDumpException if there is no data to dump
     */
    public function dump(Filesystem $filesystem, string $directory);
}
