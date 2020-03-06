<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function print_r;
use function sprintf;

final class InvalidEnumException extends Exception
{
    /**
     * @param mixed $value
     * @param array<string> $possibleValues
     *
     * @return static
     */
    public static function noValidValue($value, array $possibleValues, string $class)
    {
        return new static(
            sprintf(
                'The value \'%s\' of enum object \'%s\' is not valid. Allowed values: %s.',
                $value,
                $class,
                print_r($possibleValues, true)
            )
        );
    }

    /**
     * @return static
     */
    public static function noPossibleValues(string $class)
    {
        return new static(
            sprintf(
                'Enum object \'%s\', must have possible values.',
                $class
            )
        );
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public static function wrongType($value, string $type, string $class)
    {
        return new static(
            sprintf(
                'The type of enum object \'%s\' must be \'%s\', \'%s\' given.',
                $class,
                $type,
                (string) $value
            )
        );
    }

    /**
     * @param mixed[] $possibleValues
     *
     * @return static
     */
    public static function wrongPossibleValueTypes(array $possibleValues, string $type, string $class)
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
