<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Float;

use TeamBlue\ValueObjects\Implementation\Float\FloatRangeValue;

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
