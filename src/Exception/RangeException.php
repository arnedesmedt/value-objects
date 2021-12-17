<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function sprintf;

final class RangeException extends ValueObjectException
{
    public static function outsideRange(
        float|int $value,
        string $class,
        float|int $minimum,
        float|int $maximum,
        bool $included
    ): static {
        $orEqual = $included ? ' or equal' : '';

        return new static(
            sprintf(
                'The value \'%s\' for value object \'%s\', has to be lower%s than %s and greater%s than %s.',
                (string) $value,
                $class,
                $orEqual,
                (string) $maximum,
                $orEqual,
                (string) $minimum
            )
        );
    }
}
