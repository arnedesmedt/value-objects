<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Object;

use ADS\ValueObjects\Exception\InvalidKeyValuePairException;
use ADS\ValueObjects\KeyValuePair as KeyValuePairInterface;
use Throwable;

class KeyValuePair implements KeyValuePairInterface
{
    /** @var mixed $key */
    private $key;

    /** @var mixed $value */
    private $value;

    /**
     * @param mixed $key
     * @param mixed $value
     */
    protected function __construct($key, $value)
    {
        try {
            (string) $key;
        } catch (Throwable $exception) {
            throw InvalidKeyValuePairException::keyNotConvertableToString(static::class);
        }

        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public static function fromKeyAndValue($key, $value)
    {
        return new static($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function toKey()
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function toValue()
    {
        return $this->value;
    }

    public function toArray() : array
    {
        return [$this->toKey() => $this->toValue()];
    }

    public function __toString() : string
    {
        return print_r($this->toArray(), true);
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other) : bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toKey() === $other->toKey() &&
            $this->toValue() === $other->toValue();
    }
}
