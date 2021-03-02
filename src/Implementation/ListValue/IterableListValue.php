<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use Iterator;

use function current;
use function key;
use function next;
use function reset;

/**
 * @implements Iterator<int|string, mixed>
 */
abstract class IterableListValue extends ListValue implements Iterator
{
    /**
     * @return mixed
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
     * @return bool|float|int|string|null
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
