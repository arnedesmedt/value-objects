<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum;

use ADS\ValueObjects\EnumValue as EnumValueInterface;
use ADS\ValueObjects\Exception\EnumException;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use Stringable;

use function count;
use function in_array;
use function reset;
use function strval;

/** @phpstan-consistent-constructor */
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
            throw EnumException::noPossibleValues(static::class);
        }

        if (! in_array($value, $possibleValues)) {
            throw EnumException::noValidValue($value, $possibleValues, static::class);
        }

        $this->value = $value;
        $this->possibleValues = $possibleValues;
    }

    public static function fromValue(mixed $value): static
    {
        return new static($value);
    }

    public function toValue(): mixed
    {
        return $this->value;
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
            throw EnumException::noPossibleValues(static::class);
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
