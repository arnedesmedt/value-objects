<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ArrayAccess;
use Iterator;

use function array_key_exists;

/**
 * @implements Iterator<int, mixed>
 */
abstract class IterableListValue extends ListValue implements Iterator, ArrayAccess
{
    private int $index = 0;

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->value[$this->key()];
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->value[$this->key()]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->value);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->value[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->value[] = $value;
        } else {
            $this->value[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->value[$offset]);
    }
}
