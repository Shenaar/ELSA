<?php

namespace App\Service\ColorMapper;

use App\Service\Dumper\Contracts\CanRestore;
use App\Service\Dumper\Exceptions\NothingToRestoreException;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 *
 */
class Restorer implements CanRestore
{
    /**
     * @var CachingColorMapper
     */
    var $colorMapper;

    /**
     * @param CachingColorMapper $colorMapper
     */
    public function __construct(CachingColorMapper $colorMapper)
    {
        $this->colorMapper = $colorMapper;
    }

    /**
     * @inheritdoc
     */
    public function restore(Filesystem $filesystem, string $directory)
    {
        $path = $directory . '/mapper/';

        $this->colorMapper->clearCache();

        collect($filesystem->allFiles($path))
            ->each(function (string $file) use ($filesystem) {
                $key = array_last(explode('/', $file));

                $data = json_decode($filesystem->get($file));

                $this->colorMapper->setForKey($key, $data);
            });
    }
}
