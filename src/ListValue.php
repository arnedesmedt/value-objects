<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface ListValue extends ValueObject
{
    /**
     * @param array<mixed> $value
     *
     * @return static
     */
    public static function fromArray(array $value);

    /**
     * @param array<int,mixed> $value
     *
     * @return static
     */
    public static function fromItems(...$value); // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint

    /**
     * @return static
     */
    public function emptyList();

    /**
     * @return array<mixed>
     */
    public function toArray() : array;

    /**
     * @return array<mixed>
     */
    public function toItems() : array;

    /**
     * @param mixed $item
     *
     * @return static
     */
    public function push($item);

    /**
     * @return static
     */
    public function pop();

    /**
     * @param mixed $item
     */
    public function contains($item) : bool;

    /**
     * @return mixed|null
     */
    public function first();

    /**
     * @return mixed|null
     */
    public function last();

    public function count() : int;
}
