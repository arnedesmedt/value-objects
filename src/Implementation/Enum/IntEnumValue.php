<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum;

use TeamBlue\ValueObjects\Implementation\CalcValue;
use TeamBlue\ValueObjects\Implementation\Enum\Exception\WrongPossibleValueTypes;
use TeamBlue\ValueObjects\IntValue;

use function array_filter;
use function count;
use function intval;
use function is_int;

/** @phpstan-consistent-constructor */
abstract class IntEnumValue extends EnumValue implements IntValue
{
    use CalcValue;

    protected function __construct(int $value)
    {
        parent::__construct($value);

        $noneIntegerValues = array_filter(
            $this->possibleValues,
            static fn ($possibleValue) => ! is_int($possibleValue),
        );

        if (count($noneIntegerValues) > 0) {
            throw WrongPossibleValueTypes::fromClassAndCorrectType(
                static::class,
                'int',
            );
        }
    }

    public static function fromInt(int $value): static
    {
        return static::fromValue($value);
    }

    public static function fromValue(mixed $value): static
    {
        return new static(intval($value));
    }

    public function toInt(): int
    {
        return $this->toValue();
    }

    public function toValue(): int
    {
        return intval($this->value);
    }
}
