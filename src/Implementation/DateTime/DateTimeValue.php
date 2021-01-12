<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\DateTime;

use DateTime;
use DateTimeInterface;

abstract class DateTimeValue implements \ADS\ValueObjects\DateTimeValue
{
    protected DateTimeInterface $value;

    final protected function __construct(DateTimeInterface $value)
    {
        $this->value = $value;
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
        return $this->toDateTime()->format(self::getFormat());
    }

    public function toValue(): DateTimeInterface
    {
        return $this->toDateTime();
    }

    /**
     * @inheritDoc
     */
    public static function fromValue($value): DateTimeValue
    {
        return static::fromDateTime($value);
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

    public static function getFormat(): string
    {
        return DateTime::RFC3339_EXTENDED;
    }
}
