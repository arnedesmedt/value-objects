<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\List;

use TeamBlue\ValueObjects\Implementation\ExamplesLogic;
use TeamBlue\ValueObjects\Implementation\ListValue\JsonSchemaAwareCollectionLogic;
use TeamBlue\ValueObjects\Implementation\ListValue\ListValue;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\String\TestString;

/** @extends ListValue<TestString> */
class TestList extends ListValue
{
    use ExamplesLogic;
    use JsonSchemaAwareCollectionLogic;

    public static function itemType(): string
    {
        return TestString::class;
    }

    public static function minItems(): int|null
    {
        return 1;
    }

    public static function maxItems(): int|null
    {
        return 5;
    }

    public static function uniqueItems(): bool
    {
        return true;
    }
}
