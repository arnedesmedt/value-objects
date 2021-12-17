<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use Closure;
use Countable;
use EventEngine\Schema\TypeSchema;

interface ListValue extends ValueObject, Countable
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

    public static function fromScalarToItem(mixed $value): mixed;

    public static function fromItemToScalar(mixed $item): mixed;

    /**
     * @param array<mixed> $value
     */
    public static function fromArray(array $value): static;

    /**
     * @param array<mixed> $values
     */
    public static function fromItems(array $values): static;

    public static function emptyList(): static;

    /**
     * @return array<mixed>
     */
    public function toArray(): array;

    /**
     * @return array<mixed>
     */
    public function toItems(): array;

    public function push(mixed $item): static;

    public function put(mixed $item, ValueObject|string|null $key = null): static;

    public function pop(): static;

    public function shift(): static;

    public function unshift(mixed $item): static;

    public function forget(ValueObject|string $key): static;

    public function diffByKeys(ListValue $keys): static;

    public function get(ValueObject|string $key, mixed $default = null): mixed;

    public function getByKeys(ListValue $keys): static;

    public function has(ValueObject|string $key): bool;

    public function contains(mixed $item): bool;

    public function first(mixed $default = null): mixed;

    public function firstKey(int|string|null $default = null): string|int|null;

    public function last(mixed $default = null): mixed;

    public function lastKey(int|string|null $default = null): string|int|null;

    public function filter(Closure $closure, bool $resetKeys = false): static;

    public function isEmpty(): bool;

    public function implode(string $glue): string;

    public function unique(?Closure $closure = null): static;

    public static function containsType(): ?TypeSchema;

    public static function minItems(): ?int;

    public static function maxItems(): ?int;

    public static function uniqueItems(): ?bool;
}
