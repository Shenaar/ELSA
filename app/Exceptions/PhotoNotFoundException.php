<?php

namespace App\Exceptions;

use Carbon\Carbon;

use Exception;

class PhotoNotFoundException extends Exception
{
    public function __construct(Carbon $date)
    {
        parent::__construct("Photo for $date not found.");
    }
}
