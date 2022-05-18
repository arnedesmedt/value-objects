<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

/**
 * @template T of object
 * @template-extends IterableListValue<T>
 */
abstract class UniqueIterableListValue extends IterableListValue
{
    public static function uniqueItems(): ?bool
    {
        return true;
    }
}
