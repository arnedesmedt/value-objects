<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface FloatValue extends ValueObject
{
    /**
     * @return static
     */
    public static function fromFloat(float $value);

    public function toFloat(): float;
}
