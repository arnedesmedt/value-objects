<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\ListValue;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @template T of object
 * @template-extends ListValue<T>
 * @template-implements IteratorAggregate<string|int, T>
 */
abstract class IterableListValue extends ListValue implements IteratorAggregate
{
    /** @return Traversable<string|int, T> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->value);
    }
}
