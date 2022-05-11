<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @template T
 * @extends ListValue<T>
 * @implements IteratorAggregate<string|int, T>
 * @implements ArrayAccess<string|int, T>
 * @implements \ADS\ValueObjects\ListValue<T>
 */
abstract class IterableListValue extends ListValue implements IteratorAggregate
{
    /**
     * @return Traversable<string|int, T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->value);
    }
}
