<?php

namespace App\Service\PhotoStorage\SmartPhotoStorage\Commands;

use App\Service\PhotoStorage\SmartPhotoStorage\SmartPhotoStorage;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

use Symfony\Component\Console\Input\InputArgument;

/**
 * Dumps the cache of missing photos to a file.
 */
class DumpCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'smart:dump';

    /**
     * @var SmartPhotoStorage
     */
    private $storage;

    /**
     * DumpCommand constructor.
     *
     * @param SmartPhotoStorage $storage
     */
    public function __construct(SmartPhotoStorage $storage)
    {
        parent::__construct();

        $this->storage = $storage;

        $defaultFilename = './' . Carbon::now()->format('d.m.Y H:i') . '.dump';
        $this->addArgument('filename', InputArgument::OPTIONAL, '', $defaultFilename);
    }

    /**
     * @param Filesystem $fs
     */
    public function handle(Filesystem $fs)
    {
        $filename = $this->argument('filename');

        $fs->put($filename, $this->storage->getCache()->implode(PHP_EOL));

        $this->output->success($this->storage->getCache()->count() . ' records dumped.');
    }
}
