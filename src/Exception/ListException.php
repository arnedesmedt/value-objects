<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use ADS\Util\Util;
use ADS\ValueObjects\ValueObject;
use EventEngine\Data\ImmutableRecord;

use function sprintf;

final class ListException extends ValueObjectException
{
    public static function noValidItemType(mixed $item, string $validType, string $class): static
    {
        return new static(
            sprintf(
                'The type \'%s\' for list \'%s\' is not a valid list item type. Only \'%s\' is allowed.',
                Util::type($item),
                $class,
                $validType
            )
        );
    }

    public static function fromScalarToItemNotImplemented(string $class): static
    {
        return new static(
            sprintf(
                'The method \'fromScalarToItem\' has to be overridden for class \'%s\' ' .
                'because it\'s not an instance of \'%s\' or \'%s\'.',
                $class,
                ImmutableRecord::class,
                ValueObject::class
            )
        );
    }

    public static function fromItemToScalarNotImplemented(string $class): static
    {
        return new static(
            sprintf(
                'The method \'fromItemToScalar\' has to be overridden for class \'%s\' ' .
                'because it\'s not an instance of \'%s\' or \'%s\'.',
                $class,
                ImmutableRecord::class,
                ValueObject::class
            )
        );
    }

    public static function itemTypeNotFound(string $itemType, string $class): static
    {
        return new static(
            sprintf(
                'Could not found item type \'%s\' for list \'%s\'.',
                $itemType,
                $class
            )
        );
    }

    public static function noItemIdentifierFound(string $class, string $itemType): static
    {
        return new static(
            sprintf(
                'The class \'%s\' must override \'itemIdentifier\' ' .
                'because the item types \'%s\' are no value objects.',
                $class,
                $itemType
            )
        );
    }

    public static function valueIsNotScalar(object $value): static
    {
        return new static(
            sprintf(
                'The given value is not a scalar: \'%s\'.',
                $value::class
            )
        );
    }
}
