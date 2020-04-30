<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function sprintf;

final class ExampleException extends Exception
{
    /**
     * @return static
     */
    public static function noExampleFound(string $class)
    {
        return new static(
            sprintf(
                'No example found for value object \'%s\'.',
                $class
            )
        );
    }
}
