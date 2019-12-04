<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum;

use ADS\ValueObjects\Exception\InvalidEnumException;

abstract class StringEnumValue extends EnumValue
{
    /**
     * @param mixed $value
     */
    protected function __construct($value)
    {
        if (! is_string($value)) {
            throw InvalidEnumException::wrongType($value, 'string', static::class);
        }

        parent::__construct($value);

        $noneStringValues = array_filter($this->possibleValues, fn ($possibleValue) => ! is_string($possibleValue));

        if (count($noneStringValues) <= 0) {
            return;
        }

        throw InvalidEnumException::wrongPossibleValueTypes($noneStringValues, 'string', static::class);
    }

    /**
     * @return static
     */
    public static function fromString(string $value)
    {
        return static::fromValue($value);
    }

    public function toString() : string
    {
        return (string) $this->toValue();
    }
}
