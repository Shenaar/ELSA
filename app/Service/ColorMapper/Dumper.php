<?php

namespace App\Service\ColorMapper;

use App\Service\Dumper\Contracts\CanDump;

use App\Service\Dumper\Exceptions\NothingToDumpException;

use Illuminate\Contracts\Filesystem\Filesystem;

/**
 *
 */
class Dumper implements CanDump
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
    public function dump(Filesystem $filesystem, string $directory)
    {
        $keys = $this->colorMapper->getCachedKeys(true);

        if (count($keys) === 0) {
            throw new NothingToDumpException();
        }

        $path = $directory . '/mapper/';
        $filesystem->makeDirectory($path);

        $keys->each(function (string $key) use ($path, $filesystem) {

            $filesystem->put($path . $key, json_encode($this->colorMapper->getForKey($key)));
        });
    }
}
