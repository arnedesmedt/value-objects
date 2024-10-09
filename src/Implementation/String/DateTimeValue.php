<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use DateTime;
use DateTimeInterface;
use EventEngine\JsonSchema\Type\StringType;
use TeamBlue\ValueObjects\Exception\DateTimeException;
use TeamBlue\ValueObjects\HasExamples;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;

use function strtotime;
use function strval;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class DateTimeValue implements \TeamBlue\ValueObjects\DateTimeValue, HasExamples
{
    use ExamplesLogic;

    final protected function __construct(protected DateTimeInterface $value)
    {
    }

    public static function fromString(string $value): static
    {
        $time = strtotime($value);

        if ($time === false) {
            throw DateTimeException::noValidDateTime($value, static::class);
        }

        return new static(new DateTime('@' . $time));
    }

    public function toString(): string
    {
        return $this->toFormattedString(DateTimeInterface::RFC3339_EXTENDED);
    }

    public function toFormattedString(string $format): string
    {
        return $this->toDateTime()->format($format);
    }

    public static function fromDateTime(DateTimeInterface $value): static
    {
        return new static($value);
    }

    public function toDateTime(): DateTimeInterface
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toValue(): string
    {
        return $this->toString();
    }

    public static function fromValue(mixed $value): static
    {
        return static::fromString(strval($value));
    }

    public static function now(): static
    {
        return static::fromString('now');
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toDateTime()->getTimestamp() === $other->toDateTime()->getTimestamp();
    }

    public static function example(): static
    {
        return static::fromDateTime(new DateTime('now'));
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'date-time'];
    }
}
