<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Exception;

use function sprintf;

final class IpException extends ValueObjectException
{
    public static function noValidIpv4(string $value, string $class): static
    {
        return new static(
            sprintf(
                '\'%s\' is not a valid ipv4 for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }

    public static function noValidIpv6(string $value, string $class): static
    {
        return new static(
            sprintf(
                '\'%s\' is not a valid ipv6 for value object \'%s\'.',
                $value,
                $class,
            ),
        );
    }
}
