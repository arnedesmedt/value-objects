<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function sprintf;

final class InvalidListException extends Exception
{
    /**
     * @return static
     */
    public static function noValidItemType(string $givenType, string $validType, string $class)
    {
        return new static(
            sprintf(
                'The type \'%s\' for list \'%s\' is not a valid list item type. Only \'%s\' is allowed.',
                $givenType,
                $class,
                $validType
            )
        );
    }
}
