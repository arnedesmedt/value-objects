<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use Closure;
use Countable;
use EventEngine\Schema\TypeSchema;
use Throwable;

/**
 * @template T
 */
interface ListValue extends ValueObject, Countable
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
     * @return static<T>
     */
    public static function fromArray(array $value);

    /**
     * @param array<T> $values
     *
     * @return static<T>
     */
    public static function fromItems(array $values);

    /**
     * @return static<T>
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
     * @param T $item
     *
     * @return static<T>
     */
    public function push($item);

    /**
     * @param ValueObject|string|int|null $key
     * @param T $item
     *
     * @return static<T>
     */
    public function put($item, $key = null);

    /**
     * @return static<T>
     */
    public function pop();

    /**
     * @return static<T>
     */
    public function shift();

    /**
     * @param T $item
     *
     * @return static<T>
     */
    public function unshift($item);

    /**
     * @param ValueObject|string|int $key
     *
     * @return static<T>
     */
    public function forget($key);

    /**
     * @param ListValue<ValueObject|string|int> $keys
     *
     * @return static<T>
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
     * @param ListValue<ValueObject|string|int> $keys
     *
     * @return static<T>
     */
    public function getByKeys(ListValue $keys);

    /**
     * @param ValueObject|string|int $key
     */
    public function has($key): bool;

    /**
     * @param T $item
     */
    public function contains($item): bool;

    /**
     * @param T $item
     * @param string|int|null $default
     *
     * @return string|int|null
     */
    public function keyByItem($item, $default = null);

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
     * @return static<T>
     */
    public function filter(Closure $closure, bool $resetKeys = false);

    public function isEmpty(): bool;

    public function implode(string $glue): string;

    /**
     * @return static<T>
     */
    public function unique();

    public static function containsType(): ?TypeSchema;

    public static function minItems(): ?int;

    public static function maxItems(): ?int;

    public static function uniqueItems(): ?bool;
}
