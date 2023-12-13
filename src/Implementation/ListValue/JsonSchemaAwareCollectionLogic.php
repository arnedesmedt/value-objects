<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ADS\JsonImmutableObjects\TypeDetector;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\Schema\TypeSchema;

/** @method static class-string itemType() */
trait JsonSchemaAwareCollectionLogic
{
    use \EventEngine\JsonSchema\JsonSchemaAwareCollectionLogic;

    /** @return class-string */
    private static function __itemType(): string
    {
        return static::itemType();
    }

    public static function __itemSchema(): TypeSchema
    {
        // phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
        if (self::$__itemSchema === null) {
            $itemType = self::__itemType();

            if (self::isScalarType($itemType)) {
                return JsonSchema::schemaFromScalarPhpType($itemType, false);
            }

            self::$__itemSchema = self::__allowNestedSchema()
                ? TypeDetector::typeFromClass($itemType)
                : TypeDetector::typeFromClassAsReference($itemType);
        }

        return self::$__itemSchema;
    }

    /** @phpcsSuppress SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod */
    private static function __allowNestedSchema(): bool
    {
        return true;
    }
}
