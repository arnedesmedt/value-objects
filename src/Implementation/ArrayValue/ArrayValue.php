<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ArrayValue;

use ADS\ValueObjects\ArrayValue as ArrayValueInterface;

abstract class ArrayValue implements ArrayValueInterface
{
    /** @var mixed[] */
    protected $value;

    /**
     * @param mixed[] $value
     */
    protected function __construct(array $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $value)
    {
        return new static($value);
    }

    public function toArray() : array
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return print_r($this->toArray(), true);
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other) : bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return empty(array_diff($this->toArray(), $other->toArray())) && empty(array_diff($other->toArray(), $this->toArray()));
    }

    /**
     * @inheritDoc
     */
    public function push($item)
    {
        $clone = clone $this;

        array_push($clone->value, $item);

        return $clone;
    }
}
