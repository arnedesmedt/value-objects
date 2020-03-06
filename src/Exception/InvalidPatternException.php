<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;
use function sprintf;

final class InvalidPatternException extends Exception
{
    /**
     * @return static
     */
    public static function noMatch(string $value, string $pattern, string $class)
    {
        return new static(
            sprintf(
                'The value \'%s\' does not match pattern \'%s\' for \'%s\'.',
                $value,
                $pattern,
                $class
            )
        );
    }
}
