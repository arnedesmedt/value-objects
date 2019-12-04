<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;

final class InvalidObjectException extends Exception
{
    /**
     * @return static
     */
    public static function valuesAreNoKeyValuePairs(string $class)
    {
        return new static(
            sprintf(
                'The values of object value \'%s\' must be key value pairs.',
                $class,
            )
        );
    }
}
