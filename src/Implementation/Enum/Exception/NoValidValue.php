<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum\Exception;

use ADS\ValueObjects\EnumValue;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use function json_encode;
use function sprintf;

use const JSON_THROW_ON_ERROR;

class NoValidValue extends BadRequestHttpException
{
    /** @param class-string<EnumValue> $class */
    public static function fromInvalidValueAndClass(string|int $invalidValue, string $class): self
    {
        return new self(sprintf(
            'The value \'%s\' of enum object \'%s\' is not valid. Allowed values: %s.',
            $invalidValue,
            $class,
            json_encode($class::possibleValues(), JSON_THROW_ON_ERROR),
        ));
    }
}
