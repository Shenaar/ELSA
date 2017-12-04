<?php

namespace App\Service\PhotoStorage\LocalPhotoStorage;

use Illuminate\Console\Command;

/**
 *
 */
class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'local:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the local photos cache.';

    /**
     * StatusReport constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param LocalPhotoStorage $storage
     */
    public function handle(LocalPhotoStorage $storage)
    {
        if (!$this->confirm('Are you sure?')) {
            return;
        }

        collect($storage->getFileSystem()->directories())->each(function (string $item) use ($storage) {
            $storage->getFileSystem()->deleteDirectory($item);
        });

        $this->output->writeln('Local cache cleared successfully.');
    }
}
