<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ADS\ValueObjects\Implementation\TypeDetector;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\Schema\TypeSchema;

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

    public static function __itemSchema(): TypeSchema
    {
        // phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps
        if (self::$__itemSchema === null) {
            $itemType = self::__itemType();

            if ($itemType === null) {
                return JsonSchema::any();
            }

            if (self::isScalarType($itemType)) {
                return JsonSchema::schemaFromScalarPhpType($itemType, false);
            }

            self::$__itemSchema = TypeDetector::getTypeFromClass(
                $itemType,
                self::__allowNestedSchema(),
                isset($_GET['complex'])
            );
        }

        return self::$__itemSchema;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
     */
    private static function __allowNestedSchema(): bool
    {
        return true;
    }
}
