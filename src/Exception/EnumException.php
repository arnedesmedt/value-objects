<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function print_r;
use function sprintf;
use function strval;

final class EnumException extends ValueObjectException
{
    /**
     * @param array<int|string> $possibleValues
     *
     * @return static
     */
    public static function noValidValue(int|string $value, array $possibleValues, string $class): static
    {
        return new static(
            sprintf(
                'The value \'%s\' of enum object \'%s\' is not valid. Allowed values: %s.',
                (string) $value,
                $class,
                print_r($possibleValues, true)
            )
        );
    }

    /**
     * @return static
     */
    public static function noPossibleValues(string $class): static
    {
        return new static(
            sprintf(
                'Enum object \'%s\', must have possible values.',
                $class
            )
        );
    }

    /**
     * @return static
     */
    public static function wrongType(mixed $value, string $type, string $class): static
    {
        return new static(
            sprintf(
                'The type of enum object \'%s\' must be \'%s\', \'%s\' given.',
                $class,
                $type,
                strval($value)
            )
        );
    }

    /**
     * @param int[]|string[] $possibleValues
     *
     * @return static
     */
    public static function wrongPossibleValueTypes(array $possibleValues, string $type, string $class): static
    {
        return new static(
            sprintf(
                'Enum object \'%s\', must have possible values of the type \'%s\'. ' .
                'Invalid possible values \'%s\' detected.',
                $class,
                $type,
                print_r($possibleValues, true)
            )
        );
    }
}
