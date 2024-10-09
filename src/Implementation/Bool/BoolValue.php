<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Bool;

use Stringable;
use TeamBlue\ValueObjects\BoolValue as BoolValueInterface;
use TeamBlue\ValueObjects\HasExamples;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;

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
        return static::fromBool(true);
    }
}
