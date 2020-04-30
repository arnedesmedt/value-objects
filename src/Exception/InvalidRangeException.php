<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function sprintf;

final class InvalidRangeException extends Exception
{
    /**
     * @return static
     */
    public static function outsideRange(int $value, string $class, int $minimum, int $maximum, bool $included)
    {
        $orEqual = $included ? 'or equal' : '';

        return new static(
            sprintf(
                sprintf(
                    'The value \'%d\' for value object \'%s\', has to be lower%s than %d and greater%s than %d.',
                    $value,
                    $class,
                    $orEqual,
                    $maximum,
                    $orEqual,
                    $minimum
                )
            )
        );
    }
}
