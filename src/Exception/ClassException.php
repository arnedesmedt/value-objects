<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception;

use Exception;

use function sprintf;

final class ClassException extends Exception
{
    public static function fullQualifiedClassNameWithoutBackslash(string $className): static
    {
        return new static(
            sprintf(
                'Could not find a \\ in the full qualified class name \'%s\'.',
                $className
            )
        );
    }
}
