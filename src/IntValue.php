<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface IntValue extends ValueObject
{
    /**
     * @return static
     */
    public static function fromInt(int $value);

    public function toInt() : int;
}
