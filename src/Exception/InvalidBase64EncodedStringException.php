<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function sprintf;

final class InvalidBase64EncodedStringException extends Exception
{
    /**
     * @return static
     */
    public static function couldNotDecode(string $string, string $class)
    {
        return new static(
            sprintf(
                'Could not base64 decode string \'%s\' for value object \'%s\'.',
                $string,
                $class
            )
        );
    }
}
