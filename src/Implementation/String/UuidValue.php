<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TeamBlue\ValueObjects\HasExamples;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;

use function strval;

/** @phpstan-consistent-constructor */
abstract class UuidValue implements \TeamBlue\ValueObjects\UuidValue, HasExamples, ProvidesValidationRules
{
    use ExamplesLogic;

    protected function __construct(protected UuidInterface $value)
    {
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4());
    }

    public static function fromString(string $value): static
    {
        return new static(Uuid::fromString($value));
    }

    public function toString(): string
    {
        return $this->value->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toValue(): mixed
    {
        return $this->toString();
    }

    public static function fromValue(mixed $value): static
    {
        return static::fromString(strval($value));
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->value->equals($other->value);
    }

    public static function example(): static
    {
        return static::generate();
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [
            StringType::FORMAT => 'uuid',
            StringType::PATTERN => Uuid::VALID_PATTERN,
        ];
    }
}
