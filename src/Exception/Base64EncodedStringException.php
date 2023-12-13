<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function sprintf;

final class Base64EncodedStringException extends ValueObjectException
{
    public static function couldNotDecode(string $string, string $class): static
    {
        return new static(
            sprintf(
                'Could not base64 decode string \'%s\' for value object \'%s\'.',
                $string,
                $class,
            ),
        );
    }
}
