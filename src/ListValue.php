<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use Closure;

interface ListValue extends ValueObject
{
    /**
     * @return class-string
     */
    public static function itemType(): string;

    /**
     * This returns a closure that will get an item as argument
     * and returns the identifier of that item.
     */
    public static function itemIdentifier(): Closure;

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function fromScalarToItem($value);

    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public static function fromItemToScalar($item);

    /**
     * @param array<mixed> $value
     *
     * @return static
     */
    public static function fromArray(array $value);

    /**
     * @param array<mixed> $values
     *
     * @return static
     */
    public static function fromItems(array $values);

    /**
     * @return static
     */
    public static function emptyList();

    /**
     * @return array<mixed>
     */
    public function toArray(): array;

    /**
     * @return array<mixed>
     */
    public function toItems(): array;

    /**
     * @param mixed $item
     *
     * @return static
     */
    public function push($item);

    /**
     * @param mixed $item
     * @param ValueObject|string|null $key
     *
     * @return static
     */
    public function put($item, $key = null);

    /**
     * @return static
     */
    public function pop();

    /**
     * @return static
     */
    public function shift();

    /**
     * @param mixed $item
     *
     * @return static
     */
    public function unshift($item);

    /**
     * @param ValueObject|string $key
     *
     * @return static
     */
    public function forget($key);

    /**
     * @param ValueObject|string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param ValueObject|string $key
     */
    public function has($key): bool;

    /**
     * @param mixed|Closure $item
     */
    public function contains($item): bool;

    /**
     * @param mixed $default
     *
     * @return mixed|null
     */
    public function first($default = null);

    /**
     * @param mixed $default
     *
     * @return mixed|null
     */
    public function last($default = null);

    /**
     * @return static
     */
    public function filter(Closure $closure);

    public function count(): int;

    public function isEmpty(): bool;

    public function implode(string $glue): string;

    /**
     * @return static
     */
    public function unique(?Closure $closure = null);
}
