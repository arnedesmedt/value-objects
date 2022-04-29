<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

abstract class UniqueIterableListValue extends IterableListValue
{
    public static function uniqueItems(): ?bool
    {
        return true;
    }
}
