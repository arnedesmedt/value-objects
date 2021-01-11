<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use Iterator;

/**
 * @implements Iterator<int, mixed>
 */
abstract class IterableListValue extends ListValue implements Iterator
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
}
