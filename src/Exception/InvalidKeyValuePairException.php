<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function sprintf;

final class InvalidKeyValuePairException extends Exception
{
    /**
     * @return static
     */
    public static function keyNotConvertableToString(string $class)
    {
        return new static(
            sprintf(
                'The key of the key value pair \'%s\' can not be converted to a string.',
                $class
            )
        );
    }
}
