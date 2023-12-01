<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Object\ValueObject\Int;

use ADS\ValueObjects\Implementation\Int\RangeValue;

class TestRangeExcluded extends RangeValue
{
    public static function minimum(): int
    {
        return 1;
    }

    public static function maximum(): int
    {
        return 15;
    }

    public static function included(): bool
    {
        return false;
    }
}
