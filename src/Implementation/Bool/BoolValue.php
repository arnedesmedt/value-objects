<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Bool;

use ADS\ValueObjects\BoolValue as BoolValueInterface;

abstract class BoolValue implements BoolValueInterface
{
    protected bool $value;

    protected function __construct(bool $value)
    {
        $this->value = $value;
    }

    public static function fromBool(bool $value): static
    {
        return new static($value);
    }

    public function toBool(): bool
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toBool() ? 'true' : 'false';
    }

    public function toValue(): mixed
    {
        return $this->toBool();
    }

    public static function fromValue(mixed $value): static
    {
        return static::fromBool((bool) $value);
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toBool() === $other->toBool();
    }
}
