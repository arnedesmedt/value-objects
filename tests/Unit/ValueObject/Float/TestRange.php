<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit\ValueObject\Float;

use ADS\ValueObjects\Implementation\Float\FloatRangeValue;

class TestRange extends FloatRangeValue
{
    public static function minimum(): float
    {
        return 0;
    }

    public static function maximum(): float
    {
        return 10.4;
    }
}
