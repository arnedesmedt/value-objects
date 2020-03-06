<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface ArrayValue extends ValueObject
{
    /**
     * @param mixed $item
     *
     * @return static
     */
    public function push($item);

    /**
     * @param array<mixed> $value
     *
     * @return static
     */
    public static function fromArray(array $value);

    /**
     * @return array<mixed>
     */
    public function toArray() : array;
}
