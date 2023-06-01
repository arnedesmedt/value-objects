<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\CalcValue;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use ADS\ValueObjects\IntValue as IntValueInterface;
use Stringable;

use function intval;

/** @phpstan-consistent-constructor */
abstract class IntValue implements IntValueInterface, HasExamples, Stringable
{
    use ExamplesLogic;
    use CalcValue;

    protected function __construct(protected int $value)
    {
    }

    public static function fromInt(int $value): static
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

    public function toValue(): mixed
    {
        return $this->toInt();
    }

    public static function fromValue(mixed $value): static
    {
        return static::fromInt(intval($value));
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toInt() === $other->toInt();
    }

    public static function example(): static
    {
        return static::fromInt(32);
    }
}
