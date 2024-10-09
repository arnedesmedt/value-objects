<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\Exception\WrongPossibleValueTypes;
use TeamBlue\ValueObjects\StringValue;

use function array_filter;
use function count;
use function is_string;
use function strval;

/** @phpstan-consistent-constructor */
abstract class StringEnumValue extends EnumValue implements StringValue
{
    protected function __construct(string $value)
    {
        parent::__construct($value);

        $noneStringValues = array_filter(
            $this->possibleValues,
            static fn ($possibleValue) => ! is_string($possibleValue),
        );

        if (count($noneStringValues) > 0) {
            throw WrongPossibleValueTypes::fromClassAndCorrectType(
                static::class,
                'string',
            );
        }
    }

    public static function fromString(string $value): static
    {
        return static::fromValue($value);
    }

    public static function fromValue(mixed $value): static
    {
        return new static(strval($value));
    }

    public function toString(): string
    {
        return $this->toValue();
    }

    public function toValue(): string
    {
        return strval($this->value);
    }
}
