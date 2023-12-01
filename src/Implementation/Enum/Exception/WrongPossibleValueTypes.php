<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum\Exception;

use ADS\Exception\DefaultJsonSchemaException;
use ADS\ValueObjects\EnumValue;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use LogicException;

use function sprintf;

class WrongPossibleValueTypes extends LogicException implements JsonSchemaAwareRecord
{
    use DefaultJsonSchemaException;

    private string $title = 'No valid possible value types for enum';
    /** @var class-string<EnumValue> */
    private string $class;
    private string $correctType;

    /** @param class-string<EnumValue> $class */
    public static function fromClassAndCorrectType(string $class, string $correctType): self
    {
        return self::fromRecordData(
            [
                'class' => $class,
                'correctType' => $correctType,
            ],
        );
    }

    protected function detail(): string
    {
        return sprintf(
            'Enum object \'%s\', must have possible values of the type \'%s\'.',
            $this->class,
            $this->correctType,
        );
    }
}
