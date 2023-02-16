<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Float;

use ADS\ValueObjects\FloatValue as FloatValueInterface;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\CalcValue;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use Faker\Factory;
use Stringable;

use function floatval;

/** @phpstan-consistent-constructor */
abstract class FloatValue implements FloatValueInterface, HasExamples, Stringable
{
    use ExamplesLogic;
    use CalcValue;

    protected function __construct(protected float $value)
    {
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

    public static function example(): static
    {
        $generator = Factory::create();

        return static::fromFloat($generator->randomFloat());
    }
}
