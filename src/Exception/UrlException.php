<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function sprintf;

final class UrlException extends ValueObjectException
{
    /**
     * @return static
     */
    public static function noAsciiFormat(string $value, string $class)
    {
        return new static(
            sprintf(
                'Could not convert url \'%s\' to IDNA ASCII form for value object \'%s\'.',
                $value,
                $class
            )
        );
    }

    /**
     * @return static
     */
    public static function noValidUrl(string $value, string $class)
    {
        return new static(
            sprintf(
                '\'%s\' is not a valid url for value object \'%s\'.',
                $value,
                $class
            )
        );
    }
}
