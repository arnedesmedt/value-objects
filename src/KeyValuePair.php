<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface KeyValuePair extends ValueObject
{
    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return static
     */
    public static function fromKeyAndValue($key, $value);

    /**
     * @return mixed
     */
    public function toKey();

    /**
     * @return mixed
     */
    public function toValue();

    public function toArray() : array;
}
