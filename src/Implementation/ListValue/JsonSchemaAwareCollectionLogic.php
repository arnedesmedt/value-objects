<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

/**
 * @method static string itemType()
 */
trait JsonSchemaAwareCollectionLogic
{
    use \EventEngine\JsonSchema\JsonSchemaAwareCollectionLogic;

    /**
     * @return class-string
     */
    private static function __itemType(): string
    {
        return static::itemType();
    }
}
