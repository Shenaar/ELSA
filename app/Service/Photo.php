<?php

namespace App\Service;

use Carbon\Carbon;

/**
 * An entity for a photo.
 */
class Photo
{
    /**
     * @var Carbon
     */
    private $date;

    /**
     * @var string
     */
    private $data;

    /**
     * Photo constructor.
     * @param Carbon $date
     * @param string $data
     */
    public function __construct(Carbon $date, $data)
    {
        $this->date = $date;
        $this->data = $data;
    }

    /**
     * @return Carbon
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     *
     * @return Photo
     */
    public function setData(string $data): Photo
    {
        $this->data = $data;

        return $this;
    }
}
