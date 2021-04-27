<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use DateTime;
use DateTimeInterface;
use EventEngine\JsonSchema\Type\StringType;

use function strtotime;

abstract class DateTimeValue implements \ADS\ValueObjects\DateTimeValue
{
    protected DateTimeInterface $value;

    final protected function __construct(DateTimeInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value)
    {
        return new static(new DateTime('@' . strtotime($value)));
    }

    public function toString(): string
    {
        return $this->toFormattedString(DateTimeInterface::RFC3339_EXTENDED);
    }

    public function toFormattedString(string $format): string
    {
        return $this->toDateTime()->format($format);
    }

    public static function fromDateTime(DateTimeInterface $value): self
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

    /**
     * @inheritDoc
     */
    public static function fromValue($value): DateTimeValue
    {
        return static::fromString($value);
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toDateTime() === $other->toDateTime();
    }

    /**
     * @inheritDoc
     */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'date-time'];
    }
}
