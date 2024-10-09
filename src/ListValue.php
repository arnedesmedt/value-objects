<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

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
    /** @return class-string<T> */
    public static function itemType(): string;

    /**
     * This returns a closure that will get an item as argument
     * and returns the identifier of that item.
     */
    public static function itemIdentifier(): Closure;

    /** @return T */
    public static function fromScalarToItem(mixed $value, Closure|null $toImmutableItem = null);

    /** @return T */
    public static function fromEncryptedSensitiveDataToItem(mixed $value);

    /** @param T $item */
    public static function fromItemToScalar($item): mixed;

    /** @param array<mixed> $value */
    public static function fromArray(array $value): static;

    /** @param array<mixed> $value */
    public static function fromEncryptedSensitiveData(array $value): static;

    /** @param array<T> $values */
    public static function fromItems(array $values): static;

    public static function emptyList(): static;

    /** @return array<mixed> */
    public function toArray(): array;

    /** @return array<T> */
    public function toItems(): array;

    public function push(mixed $item): static;

    public function put(mixed $item, ValueObject|string|int|null $key = null): static;

    public function pop(): static;

    public function shift(): static;

    public function unshift(mixed $item): static;

    public function forget(ValueObject|string|int $key): static;

    /** @return array<string|int> */
    public function keys(): array;

    /** @param ListValue<ValueObject>|array<string|int> $keys */
    public function diffByKeys(ListValue|array $keys): static;

    /**
     * @param T|null $default
     *
     * @return T|null
     */
    public function get(ValueObject|string|int $key, $default = null);

    /** @return T */
    public function need(ValueObject|string|int $key, Throwable|null $exception = null);

    /** @param ListValue<ValueObject>|array<string|int> $keys */
    public function getByKeys(ListValue|array $keys): static;

    public function has(ValueObject|string|int $key): bool;

    public function contains(mixed $item): bool;

    public function keyByItem(mixed $item, string|int|null $default = null): string|int|null;

    public function needKey(mixed $item, Throwable|null $exception = null): string|int;

    /**
     * @param T|null $default
     *
     * @return T|null
     */
    public function first($default = null);

    /** @return T */
    public function needFirst(Throwable $exception);

    public function firstKey(string|int|null $default = null): string|int|null;

    /**
     * @param T|null $default
     *
     * @return T|null
     */
    public function last($default = null);

    /** @return T */
    public function needLast(Throwable $exception);

    public function lastKey(string|int|null $default = null): string|int|null;

    public function filter(Closure $closure): static;

    public function map(Closure $closure): static;

    /** @param static $list */
    public function merge($list): static;

    public function isEmpty(): bool;

    public function implode(string $glue): string;

    /** @param ListValue<T> $list */
    public function intersect(ListValue $list): static;

    public function values(): static;

    public function unique(): static;

    public function usort(Closure $closure): static;

    public static function containsType(): TypeSchema|null;

    public static function minItems(): int|null;

    public static function maxItems(): int|null;

    public static function uniqueItems(): bool|null;
}
