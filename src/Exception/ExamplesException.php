<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use function sprintf;

final class ExamplesException extends ValueObjectException
{
    public static function noExamplesFound(string $class): static
    {
        return new static(
            sprintf(
                'No examples found for value object \'%s\'.',
                $class
            )
        );
    }

    public static function noItemExamplesFound(string $itemClass, string $class): static
    {
        return new static(
            sprintf(
                'No examples found for item value object \'%s\' in array value object \'%s\'.',
                $itemClass,
                $class
            )
        );
    }
}
