<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Object\ValueObject\List;

use ADS\ValueObjects\Implementation\ExamplesLogic;
use ADS\ValueObjects\Implementation\ListValue\JsonSchemaAwareCollectionLogic;
use ADS\ValueObjects\Implementation\ListValue\ListValue;
use ADS\ValueObjects\Tests\Object\ValueObject\String\TestString;

/** @extends ListValue<TestString> */
class TestList extends ListValue
{
    use ExamplesLogic;
    use JsonSchemaAwareCollectionLogic;

    public static function itemType(): string
    {
        return TestString::class;
    }

    public static function minItems(): ?int
    {
        return 1;
    }

    public static function maxItems(): ?int
    {
        return 5;
    }

    public static function uniqueItems(): bool
    {
        return true;
    }
}
