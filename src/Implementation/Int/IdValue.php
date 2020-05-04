<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

abstract class IdValue extends RangeValue
{
    public static function minimum() : int
    {
        return 1;
    }
}
