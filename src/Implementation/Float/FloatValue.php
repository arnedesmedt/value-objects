<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Float;

use ADS\ValueObjects\FloatValue as FloatValueInterface;
use ADS\ValueObjects\Implementation\CalcValue;

use function floatval;

abstract class FloatValue implements FloatValueInterface
{
    use CalcValue;

    protected float $value;

    protected function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function fromFloat(float $value): static
    {
        return new static($value);
    }

    public function toFloat(): float
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return '' . $this->toFloat();
    }

    public function toValue(): mixed
    {
        return $this->toFloat();
    }

    public static function fromValue(mixed $value): static
    {
        return static::fromFloat(floatval($value));
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toFloat() === $other->toFloat();
    }
}
