<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Object;

use ADS\ValueObjects\Exception\InvalidObjectException;
use ADS\ValueObjects\Implementation\ArrayValue\ArrayValue;
use ADS\ValueObjects\KeyValuePair;

abstract class ObjectValue extends ArrayValue
{
    /** @var KeyValuePair[] */
    protected $value;

    protected function __construct(array $value)
    {
        if (count(array_filter($value, fn($item) => ! $item instanceof KeyValuePair))) {
            throw InvalidObjectException::valuesAreNoKeyValuePairs(static::class);
        }

        parent::__construct($value);
    }
}
