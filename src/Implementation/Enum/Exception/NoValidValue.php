<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum\Exception;

use ADS\Exception\DefaultJsonSchemaException;
use ADS\ValueObjects\EnumValue;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use RuntimeException;

use function json_encode;
use function sprintf;

use const JSON_THROW_ON_ERROR;

class NoValidValue extends RuntimeException implements JsonSchemaAwareRecord
{
    use DefaultJsonSchemaException;

    private string $title = 'No valid value for enum';
    /** @var class-string<EnumValue> */
    private string $class;
    private string $invalidValue;

    /** @param class-string<EnumValue> $class */
    public static function fromInvalidValueAndClass(string|int $invalidValue, string $class): self
    {
        return self::fromRecordData(
            [
                'class' => $class,
                'invalidValue' => (string) $invalidValue,
            ],
        );
    }

    protected function detail(): string
    {
        /** @var class-string<EnumValue> $class */
        $class = $this->class;

        return sprintf(
            'The value \'%s\' of enum object \'%s\' is not valid. Allowed values: %s.',
            $this->invalidValue,
            $class,
            json_encode($class::possibleValues(), JSON_THROW_ON_ERROR),
        );
    }
}
