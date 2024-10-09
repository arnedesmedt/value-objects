<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum\Exception;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class NoValidValueContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $class;
    private string $invalidValue;
    private string $validValues;
}
