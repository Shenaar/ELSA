<?php

namespace App\Service\PathGenerator\Generators;

use App\Service\PathGenerator\Contracts\PathGenerator;
use Carbon\Carbon;

/**
 * /ELECTRO_L_2/2017/August/11_08_2017/11082017_10 30.jpg
 */
class OldPathGenerator implements PathGenerator
{
    /**
     * @inheritdoc
     */
    public function getForDate(Carbon $date)
    {
        $month = $date->month === 3 ? 'Mart' : $date->format('F');

        return sprintf(
            '/ELECTRO_L_2/%s/%s/%s/%s.jpg',
            $date->year, $month, $date->format('d_m_Y'), $date->format('dmY_H i')
        );
    }
}
