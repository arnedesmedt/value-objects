<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Object;

use ADS\ValueObjects\Exception\InvalidObjectException;
use ADS\ValueObjects\Implementation\ArrayValue\ArrayValue;
use ADS\ValueObjects\KeyValuePair;
use function array_filter;
use function count;

abstract class ObjectValue extends ArrayValue
{
    /** @var KeyValuePair[] */
    protected array $value;

    /**
     * @param array<mixed> $value
     */
    protected function __construct(array $value)
    {
        if (count(array_filter($value, static fn($item) => ! $item instanceof KeyValuePair))) {
            throw InvalidObjectException::valuesAreNoKeyValuePairs(static::class);
        }

        parent::__construct($value);
    }
}
