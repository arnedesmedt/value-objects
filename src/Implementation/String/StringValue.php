<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use TeamBlue\ValueObjects\HasExamples;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;
use TeamBlue\ValueObjects\StringValue as StringValueInterface;

use function strval;

/** @phpstan-consistent-constructor */
abstract class StringValue implements StringValueInterface, HasExamples
{
    use ExamplesLogic;

    protected function __construct(protected string $value)
    {
    }

    public static function fromString(string $value): static
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

        return $this->toString() === $other->toString();
    }

    public static function example(): static
    {
        return static::fromString('test');
    }
}
