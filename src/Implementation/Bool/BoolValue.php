<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Bool;

use ADS\ValueObjects\BoolValue as BoolValueInterface;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use Faker\Factory;
use Stringable;

/** @phpstan-consistent-constructor */
abstract class BoolValue implements BoolValueInterface, HasExamples, Stringable
{
    use ExamplesLogic;

    protected function __construct(protected bool $value)
    {
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

    public static function example(): static
    {
        $generator = Factory::create();

        return static::fromBool($generator->boolean());
    }
}
