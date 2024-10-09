<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Int;

use TeamBlue\ValueObjects\Implementation\Int\RangeValue;

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
