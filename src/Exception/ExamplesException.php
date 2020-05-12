<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function sprintf;

final class ExamplesException extends Exception
{
    /**
     * @return static
     */
    public static function noExamplesFound(string $class)
    {
        return new static(
            sprintf(
                'No examples found for value object \'%s\'.',
                $class
            )
        );
    }

    /**
     * @return static
     */
    public static function noItemExamplesFound(string $itemClass, string $class)
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
