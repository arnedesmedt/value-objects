<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit\ValueObject\List;

use ADS\ValueObjects\Implementation\ListValue\JsonSchemaAwareCollectionLogic;
use ADS\ValueObjects\Implementation\ListValue\ListValue;

/** @extends ListValue<TestImmutable> */
class TestListImmutable extends ListValue
{
    use JsonSchemaAwareCollectionLogic;

    public static function itemType(): string
    {
        return TestImmutable::class;
    }
}
