<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function sprintf;

final class RangeException extends ValueObjectException
{
    /**
     * @param float|int $value
     * @param float|int $minimum
     * @param float|int $maximum
     *
     * @return static
     */
    public static function outsideRange(
        $value,
        string $class,
        $minimum,
        $maximum,
        bool $included
    ) {
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
