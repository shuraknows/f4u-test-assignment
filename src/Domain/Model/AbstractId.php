<?php

namespace App\Domain\Model;

abstract class AbstractId
{

    /**
     * @var mixed
     */
    private $value;

    /**
     * AbstractId constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string)$this->value;
    }
}