<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum;

use ADS\Exception\Attribute\Throws;
use ADS\ValueObjects\EnumValue as EnumValueInterface;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\Enum\Exception\NoPossibleValues;
use ADS\ValueObjects\Implementation\Enum\Exception\NoValidValue;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use Stringable;

use function count;
use function in_array;
use function reset;
use function strval;

/** @phpstan-consistent-constructor */
#[Throws([NoPossibleValues::class, NoValidValue::class])]
abstract class EnumValue implements EnumValueInterface, HasExamples, ProvidesValidationRules, Stringable
{
    use ExamplesLogic;

    protected int|string $value;

    /** @var int[]|string[] */
    protected array $possibleValues;

    protected function __construct(string|int $value)
    {
        $possibleValues = static::possibleValues();

        if (count($possibleValues) <= 0) {
            throw NoPossibleValues::fromClass(static::class);
        }

        if (! in_array($value, $possibleValues)) {
            throw NoValidValue::fromInvalidValueAndClass($value, static::class);
        }

        $this->value = $value;
        $this->possibleValues = $possibleValues;
    }

    public function __toString(): string
    {
        return strval($this->toValue());
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof static) {
            return false;
        }

        return $this->toValue() === $other->toValue();
    }

    public static function example(): static
    {
        $possibleValues = static::possibleValues();
        $possibleValue = reset($possibleValues);

        if ($possibleValue === false) {
            throw NoPossibleValues::fromClass(static::class);
        }

        return static::fromValue($possibleValue);
    }

    /** @return array<string, array<mixed>> */
    public static function validationRules(): array
    {
        return [
            StringType::ENUM => static::possibleValues(),
        ];
    }
}
