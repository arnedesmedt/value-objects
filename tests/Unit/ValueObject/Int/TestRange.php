<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit\ValueObject\Int;

use ADS\ValueObjects\Implementation\Int\RangeValue;

class TestRange extends RangeValue
{
    public static function minimum(): int
    {
        return 0;
    }

    public static function maximum(): int
    {
        return 15;
    }
}
