<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation;

use ADS\ValueObjects\BoolValue;
use ADS\ValueObjects\EnumValue;
use ADS\ValueObjects\Exception\ClassException;
use ADS\ValueObjects\FloatValue;
use ADS\ValueObjects\HasDefault;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\Enum\StringEnumValue;
use ADS\ValueObjects\Implementation\String\DateTimeValue;
use ADS\ValueObjects\IntValue;
use ADS\ValueObjects\StringValue;
use ADS\ValueObjects\ValueObject;
use DateTime;
use EventEngine\JsonSchema\AnnotatedType;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\JsonSchemaAwareCollection;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type;
use ReflectionClass;

use function array_map;
use function class_exists;
use function strrchr;
use function substr;

final class TypeDetector
{
    public static function getTypeFromClass(
        string $classOrType,
        bool $allowNestedSchema = true
    ): Type {
        if (! class_exists($classOrType)) {
            return JsonSchema::typeRef($classOrType);
        }

        $refObj = new ReflectionClass($classOrType);

        if ($refObj->implementsInterface(JsonSchemaAwareRecord::class)) {
            if ($allowNestedSchema) {
                return $classOrType::__schema();
            }

            return JsonSchema::typeRef($classOrType);
        }

        return self::determineScalarTypeOrListIfPossible($refObj)
            ?? self::convertClassToType($classOrType);
    }

    /**
     * @param ReflectionClass<object> $refObj
     */
    private static function determineScalarTypeOrListIfPossible(ReflectionClass $refObj): ?Type
    {
        $schemaType = null;
        /** @var class-string $class */
        $class = $refObj->getName();

        if ($refObj->implementsInterface(JsonSchemaAwareCollection::class)) {
            $validation = $refObj->implementsInterface(ProvidesValidationRules::class)
                ? $class::validationRules()
                : null;

            $schemaType = JsonSchema::array($class::__itemSchema(), $validation);
        }

        if (! $schemaType) {
            $schemaType = self::determineScalarTypeIfPossible($refObj);
        }

        if (! $schemaType) {
            return null;
        }

        if ($refObj->implementsInterface(HasDefault::class) && $schemaType instanceof AnnotatedType) {
            $schemaType = $schemaType->withDefault($class::defaultValue()->toValue());
        }

        if (
            ! $refObj->implementsInterface(HasExamples::class)
            || ! ($schemaType instanceof AnnotatedType)
        ) {
            return $schemaType;
        }

        return $schemaType->withExamples(
            ...array_map(
                static fn (ValueObject $valueObject) => $valueObject->toValue(),
                $class::examples()
            )
        );
    }

    /**
     * @param ReflectionClass<object> $refObj
     */
    private static function determineScalarTypeIfPossible(ReflectionClass $refObj): ?Type
    {
        /** @var class-string $class */
        $class = $refObj->getName();
        $validation = $refObj->implementsInterface(ProvidesValidationRules::class)
            ? $class::validationRules()
            : null;

        if ($refObj->implementsInterface(EnumValue::class)) {
            $possibleValues = $class::possibleValues();
            $type = $refObj->isSubclassOf(StringEnumValue::class)
                ? JsonSchema::TYPE_STRING
                : JsonSchema::TYPE_INT;

            return JsonSchema::enum($possibleValues, $type);
        }

        if ($refObj->implementsInterface(StringValue::class)) {
            return JsonSchema::string($validation);
        }

        if ($refObj->implementsInterface(IntValue::class)) {
            return JsonSchema::integer($validation);
        }

        if ($refObj->implementsInterface(FloatValue::class)) {
            return JsonSchema::float($validation);
        }

        if ($refObj->implementsInterface(BoolValue::class)) {
            return JsonSchema::boolean();
        }

        return null;
    }

    private static function convertClassToType(string $class): Type
    {
        $position = strrchr($class, '\\');

        if ($position === false) {
            switch (true) {
                case $class === DateTime::class:
                    return new Type\StringType(DateTimeValue::validationRules());

                default:
                    throw ClassException::fullQualifiedClassNameWithoutBackslash($class);
            }
        }

        $ref = substr($position, 1);

        return new Type\TypeRef($ref);
    }
}
