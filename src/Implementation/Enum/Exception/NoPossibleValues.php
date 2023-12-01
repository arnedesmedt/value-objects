<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum\Exception;

use ADS\Exception\DefaultJsonSchemaException;
use ADS\ValueObjects\Implementation\Enum\EnumValue;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use LogicException;

use function sprintf;

class NoPossibleValues extends LogicException implements JsonSchemaAwareRecord
{
    use DefaultJsonSchemaException;

    private string $title = 'No possible values for enum';
    /** @var class-string<EnumValue> */
    private string $class;

    /** @param class-string<EnumValue> $class */
    public static function fromClass(string $class): self
    {
        return self::fromRecordData(['class' => $class]);
    }

    protected function detail(): string
    {
        return sprintf(
            'The enum \'%s\' has no possible values.',
            $this->class,
        );
    }
}
