<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ArrayAccess;
use Iterator;

use function current;
use function key;
use function next;
use function reset;

/**
 * @template T
 * @implements Iterator<int|string, T>
 * @extends ListValue<T>
 * @implements ArrayAccess<string|int, T>
 * @implements \ADS\ValueObjects\ListValue<T>
 */
abstract class IterableListValue extends ListValue implements Iterator
{
    /**
     * @return T|false
     */
    public function current()
    {
        return current($this->value);
    }

    public function next(): void
    {
        next($this->value);
    }

    /**
     * @return int|string|null
     */
    public function key()
    {
        return key($this->value);
    }

    public function valid(): bool
    {
        return isset($this->value[$this->key()]);
    }

    public function rewind(): void
    {
        reset($this->value);
    }
}
