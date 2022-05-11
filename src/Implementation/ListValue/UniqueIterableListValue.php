<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ADS\ValueObjects\ListValue;
use ArrayAccess;
use IteratorAggregate;

/**
 * @template T
 * @extends IterableListValue<T>
 * @extends ListValue<T>
 * @implements ArrayAccess<string|int, T>
 * @implements ListValue<T>
 * @implements IteratorAggregate<string|int, T>
 */
abstract class UniqueIterableListValue extends IterableListValue
{
    public static function uniqueItems(): ?bool
    {
        return true;
    }
}
