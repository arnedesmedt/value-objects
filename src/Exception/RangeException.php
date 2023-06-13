<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function sprintf;

final class RangeException extends ValueObjectException
{
    public static function outsideRange(
        string $value,
        string $class,
        string $minimum,
        string $maximum,
        bool $included
    ): static {
        $orEqual = $included ? ' or equal' : '';

        return new static(
            sprintf(
                'The value \'%s\' for value object \'%s\', has to be lower%s than %s and greater%s than %s.',
                $value,
                $class,
                $orEqual,
                $maximum,
                $orEqual,
                $minimum
            )
        );
    }

    public static function outsideRangeFromNumber(
        float|int $value,
        string $class,
        float|int $minimum,
        float|int $maximum,
        bool $included
    ): static {
        return self::outsideRange(
            (string) $value,
            $class,
            (string) $minimum,
            (string) $maximum,
            $included
        );
    }
}
