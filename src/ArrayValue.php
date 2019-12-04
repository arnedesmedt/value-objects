<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface ArrayValue
{
    /**
     * @param mixed $item
     *
     * @return static
     */
    public function push($item);

    /**
     * @return static
     */
    public static function fromArray(array $value);

    public function toArray() : array;
}
