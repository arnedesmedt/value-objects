<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Int;

use TeamBlue\ValueObjects\Implementation\Int\RangeValue;

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
