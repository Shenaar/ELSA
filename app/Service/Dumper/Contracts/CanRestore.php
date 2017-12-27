<?php

namespace App\Service\Dumper\Contracts;

use App\Service\Dumper\Exceptions\NothingToRestoreException;

use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Represents something that can restore data.
 */
interface CanRestore
{
    /**
     * @param Filesystem $filesystem
     * @param string $directory
     *
     * @throws NothingToRestoreException if there is nothing to restore
     */
    public function restore(Filesystem $filesystem, string $directory);
}
