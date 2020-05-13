<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface ValueObject
{
    public function __toString() : string;

    /**
     * @param mixed $other
     */
    public function isEqualTo($other) : bool;

    /**
     * @return mixed
     */
    public function toValue();
}
