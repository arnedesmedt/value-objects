<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Stringable;

use function strval;

abstract class UuidValue implements \ADS\ValueObjects\UuidValue, Stringable
{
    protected function __construct(protected UuidInterface $value)
    {
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4());
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value)
    {
        return new static(Uuid::fromString($value));
    }

    public function toString(): string
    {
        return $this->value->toString();
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
        return static::fromString(strval($value));
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->value->equals($other->value);
    }
}
