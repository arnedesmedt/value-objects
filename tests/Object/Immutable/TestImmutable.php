<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\Immutable;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;
use TeamBlue\ValueObjects\HasExamples;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;

class TestImmutable implements JsonSchemaAwareRecord, HasExamples
{
    use JsonSchemaAwareRecordLogic;
    use ExamplesLogic;

    private readonly string $test; // @phpstan-ignore-line

    public static function __type(): string
    {
        return 'myType';
    }

    public static function example(): self
    {
        return self::fromArray(['test' => 'test']);
    }
}
