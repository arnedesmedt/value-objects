<?php

declare(strict_types=1);

namespace ADS\ValueObjects\EventEngine;

use EventEngine\JsonSchema\Type;

trait JsonSchemaAwareRecordLogic
{
    use \EventEngine\JsonSchema\JsonSchemaAwareRecordLogic {
        getTypeFromClass as notUsedGetTypeFromClass;
    }

    private static function getTypeFromClass(string $classOrType) : Type
    {
        return TypeDetector::getTypeFromClass($classOrType, self::__allowNestedSchema());
    }
}
