<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\StringValue as StringValueInterface;

abstract class StringValue implements StringValueInterface
{
    protected string $value;

    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value)
    {
        return new static($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function toValue()
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public static function fromValue($value)
    {
        return static::fromString($value);
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
}
