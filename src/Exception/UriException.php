<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use function sprintf;

final class UriException extends ValueObjectException
{
    public static function noAsciiFormat(string $value, string $class): static
    {
        return new static(
            sprintf(
                'Could not convert url \'%s\' to IDNA ASCII form for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }

    public static function noValidUrl(string $value, string $class): static
    {
        return new static(
            sprintf(
                '\'%s\' is not a valid url for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }

    public static function noValidUri(string $value, string $class): static
    {
        return new static(
            sprintf(
                '\'%s\' is not a valid uri for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }

    public static function noTrailingSlash(string $value, string $class): static
    {
        return new static(
            sprintf(
                '\'%s\' needs a trailing slash for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }
}
