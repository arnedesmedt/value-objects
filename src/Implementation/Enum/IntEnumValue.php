<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum;

use ADS\ValueObjects\Exception\EnumException;

use function array_filter;
use function count;
use function intval;
use function is_int;

abstract class IntEnumValue extends EnumValue
{
    protected function __construct(mixed $value)
    {
        if (! is_int($value)) {
            throw EnumException::wrongType(
                $value,
                'int',
                static::class
            );
        }

        parent::__construct($value);

        $noneIntegerValues = array_filter(
            $this->possibleValues,
            static fn ($possibleValue) => ! is_int($possibleValue)
        );

        if (count($noneIntegerValues) <= 0) {
            return;
        }

        throw EnumException::wrongPossibleValueTypes(
            $noneIntegerValues,
            'int',
            static::class
        );
    }

    /**
     * @return static
     */
    public static function fromInt(int $value): static
    {
        return static::fromValue($value);
    }

    public function toInt(): int
    {
        return intval($this->toValue());
    }
}
