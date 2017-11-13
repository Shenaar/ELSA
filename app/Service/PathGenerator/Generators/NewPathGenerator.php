<?php

namespace App\Service\PathGenerator\Generators;

use App\Service\PathGenerator\Contracts\PathGenerator;

use Carbon\Carbon;

/**
 * /ELECTRO_L_2/2017/August/11/1030/170811_1030_RGB.jpg
 */
class NewPathGenerator implements PathGenerator
{
    /**
     * @inheritdoc
     */
    public function getForDate(Carbon $date)
    {
        return $date->format('/\E\L\E\C\T\R\O\_\L\_\2/Y/F/d/Hi/ymd_Hi_\R\G\B\.\j\p\g');
    }
}
