<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum\Exception;

use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextMetadataAwareExceptionLogic;
use TeamBlue\Exception\HttpException\MetadataAwareException;
use TeamBlue\Exception\HttpException\Status\BadRequestHttpException;
use TeamBlue\ValueObjects\EnumValue;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class NoValidValue extends BadRequestHttpException implements
    ContextAwareException,
    MetadataAwareException
{
    use ContextMetadataAwareExceptionLogic;

    protected string $title = 'No valid value for enum';

    private NoValidValueContext $context;

    protected static function template(): string
    {
        return "The value '{invalidValue}' of enum object '{class}' is not valid. Allowed values: {allowedValues}.";
    }

    /** @param class-string<EnumValue> $class */
    public static function fromInvalidValueAndClass(string|int $invalidValue, string $class): self
    {
        return self::fromContextArray(
            [
                'class' => $class,
                'invalidValue' => (string) $invalidValue,
                'validValues' => json_encode($class::possibleValues(), JSON_THROW_ON_ERROR),
            ],
        );
    }
}
