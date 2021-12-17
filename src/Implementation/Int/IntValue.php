<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use ADS\ValueObjects\IntValue as IntValueInterface;
use Stringable;

use function intval;

abstract class IntValue implements IntValueInterface, Stringable
{
    protected function __construct(protected int $value)
    {
    }

    /**
     * @inheritDoc
     */
    public static function fromInt(int $value)
    {
        return new static($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return '' . $this->toInt();
    }

    /**
     * @inheritDoc
     */
    public function toValue()
    {
        return $this->toInt();
    }

    /**
     * @inheritDoc
     */
    public static function fromValue($value)
    {
        return static::fromInt(intval($value));
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toInt() === $other->toInt();
    }
}
