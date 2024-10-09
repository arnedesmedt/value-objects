<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Float;

use TeamBlue\ValueObjects\Implementation\Float\FloatRangeValue;

class TestRangeExcluded extends FloatRangeValue
{
    public static function minimum(): float
    {
        return 1.2;
    }

    public static function maximum(): float
    {
        return 6.2;
    }

    public static function included(): bool
    {
        return false;
    }
}
