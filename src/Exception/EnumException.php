<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function json_encode;
use function sprintf;
use function strval;

use const JSON_THROW_ON_ERROR;

final class EnumException extends ValueObjectException
{
    /**
     * @param array<string|int> $possibleValues
     */
    public static function noValidValue(string|int $value, array $possibleValues, string $class): static
    {
        return new static(
            sprintf(
                'The value \'%s\' of enum object \'%s\' is not valid. Allowed values: %s.',
                (string) $value,
                $class,
                json_encode($possibleValues, JSON_THROW_ON_ERROR, 512),
            )
        );
    }

    public static function noPossibleValues(string $class): static
    {
        return new static(
            sprintf(
                'Enum object \'%s\', must have possible values.',
                $class
            )
        );
    }

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
     */
    public static function wrongPossibleValueTypes(array $possibleValues, string $type, string $class): static
    {
        return new static(
            sprintf(
                'Enum object \'%s\', must have possible values of the type \'%s\'. ' .
                'Invalid possible values \'%s\' detected.',
                $class,
                $type,
                json_encode($possibleValues, JSON_THROW_ON_ERROR, 512),
            )
        );
    }
}
