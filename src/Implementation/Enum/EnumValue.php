<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum;

use ADS\ValueObjects\EnumValue as EnumValueInterface;
use ADS\ValueObjects\Exception\EnumException;

use function count;
use function in_array;
use function strval;

abstract class EnumValue implements EnumValueInterface
{
    protected mixed $value;

    /** @var int[]|string[] */
    protected array $possibleValues;

    /**
     * @param string|int $value
     */
    protected function __construct($value)
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

    /**
     * @inheritDoc
     */
    public static function fromValue($value)
    {
        return new static($value);
    }

    /**
     * @inheritDoc
     */
    public function toValue()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return strval($this->toValue());
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other): bool
    {
        if (! $other instanceof static) {
            return false;
        }

        return $this->toValue() === $other->toValue();
    }
}
