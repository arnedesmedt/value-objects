<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Int;

abstract class TimestampValue extends RangeValue
{
    public static function minimum(): int
    {
        return 0;
    }
}
