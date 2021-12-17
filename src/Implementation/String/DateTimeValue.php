<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use DateTime;
use DateTimeInterface;
use EventEngine\JsonSchema\Type\StringType;
use Stringable;

use function strtotime;
use function strval;

class DateTimeValue implements \ADS\ValueObjects\DateTimeValue, Stringable
{
    final protected function __construct(protected DateTimeInterface $value)
    {
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

    /**
     * @inheritDoc
     */
    public static function fromDateTime(DateTimeInterface $value)
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
    public static function fromValue($value)
    {
        return static::fromString(strval($value));
    }

    /**
     * @inheritDoc
     */
    public static function now()
    {
        return static::fromString('now');
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toDateTime()->getTimestamp() === $other->toDateTime()->getTimestamp();
    }

    /**
     * @return array<mixed>
     *
     * @inheritDoc
     */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'date-time'];
    }
}
