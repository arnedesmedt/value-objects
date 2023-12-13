<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function sprintf;

final class EmailException extends ValueObjectException
{
    public static function noAsciiFormat(string $value, string $class): static
    {
        return new static(
            sprintf(
                'Could not convert e-mail \'%s\' to IDNA ASCII form for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }

    public static function noValidEmail(string $value, string $class): static
    {
        return new static(
            sprintf(
                '\'%s\' is not a valid e-mail for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }
}
