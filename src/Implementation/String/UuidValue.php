<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use function strval;

abstract class UuidValue implements \ADS\ValueObjects\UuidValue
{
    protected UuidInterface $value;

    protected function __construct(UuidInterface $value)
    {
        $this->value = $value;
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4());
    }

    public static function fromString(string $value): static
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

    public function toValue(): mixed
    {
        return $this->toString();
    }

    public static function fromValue(mixed $value): static
    {
        return static::fromString(strval($value));
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->value->equals($other->value);
    }
}
