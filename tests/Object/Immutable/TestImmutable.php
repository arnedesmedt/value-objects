<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Object\Immutable;

use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\JsonSchemaAwareRecordLogic;

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
