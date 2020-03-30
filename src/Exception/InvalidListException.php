<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use EventEngine\Data\ImmutableRecord;
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

    /**
     * @return static
     */
    public static function fromScalarToItemNotImplemented(string $class)
    {
        return new static(
            sprintf(
                'The method \'fromScalarToItem\' has to be overridden for class \'%s\' because it\'s not an instance of \'%s\'.',
                $class,
                ImmutableRecord::class
            )
        );
    }

    /**
     * @return static
     */
    public static function fromItemToScalarNotImplemented(string $class)
    {
        return new static(
            sprintf(
                'The method \'fromItemToScalar\' has to be overridden for class \'%s\' because it\'s not an instance of \'%s\'.',
                $class,
                ImmutableRecord::class
            )
        );
    }

    /**
     * @return static
     */
    public static function itemTypeNotFound(string $itemType, string $class)
    {
        return new static(
            sprintf(
                'Could not found item type \'%s\' for list \'%s\'.',
                $itemType,
                $class
            )
        );
    }
}
