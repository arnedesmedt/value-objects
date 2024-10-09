<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\List;

use TeamBlue\ValueObjects\Implementation\ListValue\JsonSchemaAwareCollectionLogic;
use TeamBlue\ValueObjects\Implementation\ListValue\ListValue;
use TeamBlue\ValueObjects\Tests\Object\Immutable\TestImmutable;

/** @extends ListValue<TestImmutable> */
class TestListImmutable extends ListValue
{
    use JsonSchemaAwareCollectionLogic;

    public static function itemType(): string
    {
        return TestImmutable::class;
    }
}
