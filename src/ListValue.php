<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use ArrayAccess;
use Closure;
use Countable;
use EventEngine\Schema\TypeSchema;
use Throwable;

/**
 * @template T of object
 * @template-extends ArrayAccess<string|int, T>
 */
interface ListValue extends ValueObject, Countable, ArrayAccess
{
    /**
     * @return class-string<T>
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
     * @return T
     */
    public static function fromScalarToItem($value);

    /**
     * @param T $item
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
     * @param array<T> $values
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
     * @return array<T>
     */
    public function toItems(): array;

    /**
     * @param mixed $item
     *
     * @return static
     */
    public function push($item);

    /**
     * @param ValueObject|string|int|null $key
     * @param mixed $item
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
     * @param ValueObject|string|int $key
     *
     * @return static
     */
    public function forget($key);

    /**
     * @param ListValue<ValueObject> $keys
     *
     * @return static
     */
    public function diffByKeys(ListValue $keys);

    /**
     * @param ValueObject|string|int $key
     * @param T|null $default
     *
     * @return T|null
     */
    public function get($key, $default = null);

    /**
     * @param ValueObject|string|int $key
     *
     * @return T
     */
    public function need($key, ?Throwable $exception = null);

    /**
     * @param ListValue<ValueObject> $keys
     *
     * @return static
     */
    public function getByKeys(ListValue $keys);

    /**
     * @param ValueObject|string|int $key
     */
    public function has($key): bool;

    /**
     * @param mixed $item
     */
    public function contains($item): bool;

    /**
     * @param mixed $item
     * @param string|int|null $default
     *
     * @return string|int|null
     */
    public function keyByItem($item, $default = null);

    /**
     * @param mixed $item
     *
     * @return string|int
     */
    public function needKey($item, ?Throwable $exception = null);

    /**
     * @param T|null $default
     *
     * @return T|null
     */
    public function first($default = null);

    /**
     * @return T
     */
    public function needFirst(Throwable $exception);

    /**
     * @param string|int|null $default
     *
     * @return string|int|null
     */
    public function firstKey($default = null);

    /**
     * @param T|null $default
     *
     * @return T|null
     */
    public function last($default = null);

    /**
     * @return T
     */
    public function needLast(Throwable $exception);

    /**
     * @param string|int|null $default
     *
     * @return string|int|null
     */
    public function lastKey($default = null);

    /**
     * @return static
     */
    public function filter(Closure $closure, bool $resetKeys = false);

    /**
     * @return static
     */
    public function map(Closure $closure);

    /**
     * @param static $list
     *
     * @return static
     */
    public function merge($list);

    public function isEmpty(): bool;

    public function implode(string $glue): string;

    /**
     * @return static
     */
    public function unique();

    public static function containsType(): ?TypeSchema;

    public static function minItems(): ?int;

    public static function maxItems(): ?int;

    public static function uniqueItems(): ?bool;
}
