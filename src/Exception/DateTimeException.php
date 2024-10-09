<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use function sprintf;

final class DateTimeException extends ValueObjectException
{
    public static function noValidDateTime(string $value, string $class): static
    {
        return new static(
            sprintf(
                '\'%s\' is not a valid date time for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }
}
