<?php

namespace App\Service\Reporter;

/**
 *
 */
class Report
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * Report constructor.
     *
     * @param string $key
     * @param string $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
