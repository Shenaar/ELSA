<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage\Commands;

use App\Service\PhotoStorage\SmartPhotoStorage\SmartPhotoStorage;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 *
 */
abstract class SmartStorageCommand extends Command
{

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var SmartPhotoStorage
     */
    protected $storage;

    /**
     * SmartStorageCommand constructor.
     *
     * @param SmartPhotoStorage $storage
     * @param Filesystem $filesystem
     */
    public function __construct(SmartPhotoStorage $storage, Filesystem $filesystem)
    {
        parent::__construct();

        $this->storage = $storage;
        $this->filesystem = $filesystem;
    }
}
