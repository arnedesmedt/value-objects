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
use ADS\ValueObjects\Util;
use DateTime;
use EventEngine\JsonSchema\AnnotatedType;
use EventEngine\JsonSchema\JsonSchema;
use EventEngine\JsonSchema\JsonSchemaAwareCollection;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type;

use function array_map;
use function class_exists;
use function class_implements;
use function class_parents;
use function in_array;
use function is_array;
use function strrchr;
use function substr;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class TypeDetector
{
    /** @param string|class-string<JsonSchemaAwareRecord|mixed> $classOrType */
    public static function typeFromClass(
        string $classOrType
    ): Type {
        $type = self::typeForNonJsonSchemaAwareRecord($classOrType);

        if ($type !== null) {
            return $type;
        }

        return $classOrType::__schema();
    }

    /** @param string|class-string<JsonSchemaAwareRecord|mixed> $classOrType */
    public static function typeFromClassAsReference(string $classOrType): Type
    {
        return self::typeForNonJsonSchemaAwareRecord($classOrType) ?? JsonSchema::typeRef($classOrType);
    }

    /** @param string|class-string<JsonSchemaAwareRecord|mixed> $classOrType */
    private static function typeForNonJsonSchemaAwareRecord(string $classOrType): ?Type
    {
        if (! class_exists($classOrType)) {
            return JsonSchema::typeRef($classOrType);
        }

        $implementations = class_implements($classOrType);

        if (is_array($implementations) && in_array(JsonSchemaAwareRecord::class, $implementations)) {
            return null;
        }

        $type = self::typeFromList($classOrType)
            ?? self::typeFromEnum($classOrType)
            ?? self::typeFromValueObject($classOrType)
            ?? self::typeFromDateTime($classOrType)
            ?? self::typeFromUnknownClass($classOrType);

        if (! $type instanceof AnnotatedType) {
            return $type;
        }

        $type = self::addDefault($classOrType, $type);
        $type = self::addExamples($classOrType, $type);

        return $type;
    }

    /** @param class-string<JsonSchemaAwareCollection|mixed> $class */
    private static function typeFromList(string $class): ?Type
    {
        $implementations = class_implements($class);

        if (! $implementations || ! in_array(JsonSchemaAwareCollection::class, $implementations)) {
            return null;
        }

        return JsonSchema::array($class::__itemSchema(), self::validationRulesFromClass($class));
    }

    /** @param class-string<EnumValue|mixed> $class */
    private static function typeFromEnum(string $class): ?Type
    {
        $implementations = class_implements($class);
        $parentClasses = class_parents($class);

        if (! $implementations || ! in_array(EnumValue::class, $implementations)) {
            return null;
        }

        $possibleValues = $class::possibleValues();
        $type = $parentClasses && in_array(StringEnumValue::class, $parentClasses)
            ? JsonSchema::TYPE_STRING
            : JsonSchema::TYPE_INT;

        return JsonSchema::enum($possibleValues, $type);
    }

    /** @param class-string<JsonSchemaAwareCollection|ProvidesValidationRules|mixed> $class */
    private static function typeFromValueObject(string $class): ?Type
    {
        $implementations = class_implements($class);

        if (! $implementations) {
            return null;
        }

        if (in_array(BoolValue::class, $implementations)) {
            return JsonSchema::boolean();
        }

        $validationRules = self::validationRulesFromClass($class);

        if (in_array(StringValue::class, $implementations)) {
            return JsonSchema::string($validationRules);
        }

        if (in_array(IntValue::class, $implementations)) {
            return JsonSchema::integer($validationRules);
        }

        if (in_array(FloatValue::class, $implementations)) {
            return JsonSchema::float($validationRules);
        }

        return null;
    }

    /**
     * @param class-string<ProvidesValidationRules|mixed> $class
     *
     * @return array<string, mixed>|null
     */
    private static function validationRulesFromClass(string $class): ?array
    {
        $implementations = class_implements($class);

        if (! $implementations || ! in_array(ProvidesValidationRules::class, $implementations)) {
            return null;
        }

        return $class::validationRules();
    }

    private static function typeFromDateTime(string $class): ?Type
    {
        $lastPart = strrchr($class, '\\');

        return $lastPart === false && $class === DateTime::class
            ? new Type\StringType(DateTimeValue::validationRules())
            : null;
    }

    private static function typeFromUnknownClass(string $class): Type
    {
        $lastPart = strrchr($class, '\\');

        if ($lastPart === false) {
            throw ClassException::fullQualifiedClassNameWithoutBackslash($class);
        }

        $ref = substr($lastPart, 1);

        return new Type\TypeRef($ref);
    }

    /** @param class-string<HasDefault|mixed> $class */
    private static function addDefault(string $class, AnnotatedType $type): AnnotatedType
    {
        $implementations = class_implements($class);

        if (! $implementations || ! in_array(HasDefault::class, $implementations)) {
            return $type;
        }

        return $type->withDefault(Util::toScalar($class::defaultValue()));
    }

    /** @param class-string<HasExamples|mixed> $class */
    private static function addExamples(string $class, AnnotatedType $type): AnnotatedType
    {
        $implementations = class_implements($class);

        if (! $implementations || ! in_array(HasExamples::class, $implementations)) {
            return $type;
        }

        $examples = $class::examples();

        if (empty($examples)) {
            return $type;
        }

        return $type->withExamples(
            ...array_map(
                static fn (mixed $example) => Util::toScalar($example),
                $examples
            )
        );
    }
}
