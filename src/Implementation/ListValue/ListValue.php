<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ADS\ValueObjects\Exception\ListException;
use ADS\ValueObjects\ValueObject;
use ArrayAccess;
use Closure;
use EventEngine\Data\ImmutableRecord;
use EventEngine\JsonSchema\JsonSchemaAwareCollection;
use EventEngine\Schema\TypeSchema;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Stringable;

use function array_diff;
use function array_diff_key;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_key_exists;
use function array_key_first;
use function array_key_last;
use function array_keys;
use function array_map;
use function array_pop;
use function array_push;
use function array_reverse;
use function array_shift;
use function array_unique;
use function array_unshift;
use function array_values;
use function count;
use function gettype;
use function implode;
use function is_array;
use function is_object;
use function is_scalar;
use function print_r;
use function reset;
use function sprintf;
use function strval;

/**
 * @template-implements ArrayAccess<string|int, mixed>
 */
abstract class ListValue implements \ADS\ValueObjects\ListValue, JsonSchemaAwareCollection, ArrayAccess, Stringable
{
    /** @var mixed[] */
    protected array $value;

    /**
     * @param array<mixed> $values
     */
    protected function __construct(array $values)
    {
        $this->value = $values;
    }

    public static function itemIdentifier(): Closure
    {
        return static function ($item) {
            if ($item instanceof ValueObject) {
                return $item->__toString();
            }

            throw ListException::noItemIdentifierFound(
                static::class,
                static::itemType()
            );
        };
    }

    /**
     * @return class-string
     *
     * @phpstan
     */
    private static function __itemType(): string
    {
        return static::itemType();
    }

    private static function __allowNestedSchema(): bool
    {
        return true;
    }

    public static function fromScalarToItem(mixed $value): mixed
    {
        if (is_object($value)) {
            throw ListException::valueIsNotScalar($value);
        }

        $itemType = static::itemType();

        try {
            $relfectionClass = new ReflectionClass($itemType);

            if ($relfectionClass->implementsInterface(ImmutableRecord::class)) {
                return $itemType::fromArray($value);
            }

            if ($relfectionClass->implementsInterface(ValueObject::class)) {
                return $itemType::fromValue($value);
            }

            throw ListException::fromScalarToItemNotImplemented(static::class);
        } catch (ReflectionException) {
            throw ListException::itemTypeNotFound($itemType, static::class);
        }
    }

    public static function fromItemToScalar(mixed $item): mixed
    {
        $itemType = static::itemType();

        try {
            $relfectionClass = new ReflectionClass($itemType);

            if (
                $relfectionClass->implementsInterface(ImmutableRecord::class)
                && $item instanceof ImmutableRecord
            ) {
                return $item->toArray();
            }

            if (
                $relfectionClass->implementsInterface(ValueObject::class)
                && $item instanceof ValueObject
            ) {
                return $item->toValue();
            }

            throw ListException::fromItemToScalarNotImplemented(static::class);
        } catch (ReflectionException) {
            throw ListException::itemTypeNotFound($itemType, static::class);
        }
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $value): static
    {
        return static::fromItems(array_map(
            static fn ($array) => static::fromScalarToItem($array),
            $value
        ));
    }

    /**
     * @inheritDoc
     */
    public static function fromItems(array $values): static
    {
        self::checkTypes(...$values);

        return new static($values);
    }

    public static function emptyList(): static
    {
        return new static([]);
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return array_map(
            static fn ($item) => static::fromItemToScalar($item),
            $this->value
        );
    }

    /**
     * @return array<mixed>
     */
    public function toItems(): array
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return print_r($this->toArray(), true);
    }

    /**
     * @inheritDoc
     */
    public function toValue()
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public static function fromValue($value)
    {
        if (! is_array($value)) {
            throw new RuntimeException('No array given.');
        }

        return static::fromArray($value);
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return empty(
            array_diff(
                $this->toArray(),
                $other->toArray()
            )
        )
            && empty(
                array_diff(
                    $other->toArray(),
                    $this->toArray()
                )
            );
    }

    public function push(mixed $item): static
    {
        $clone = clone $this;

        array_push($clone->value, static::toItem($item));

        return $clone;
    }

    public function put(mixed $item, ValueObject|string|int|null $key = null): static
    {
        $clone = clone $this;
        $item = static::toItem($item);

        if ($key === null) {
            $key = (static::itemIdentifier())($item);
        }

        $key = (string) $key;

        $clone->value[$key] = $item;

        return $clone;
    }

    public function pop(): static
    {
        $clone = clone $this;

        array_pop($clone->value);

        return $clone;
    }

    public function shift(): static
    {
        $clone = clone $this;

        array_shift($clone->value);

        return $clone;
    }

    public function unshift(mixed $item): static
    {
        $clone = clone $this;

        array_unshift($clone->value, static::toItem($item));

        return $clone;
    }

    public function forget(ValueObject|string|int $key): static
    {
        $clone = clone $this;

        $key = (string) $key;

        unset($clone->value[$key]);

        return $clone;
    }

    public function diffByKeys(\ADS\ValueObjects\ListValue $keys): static
    {
        $clone = clone $this;

        /** @var array<int|string> $keys */
        $keys = $keys->toArray();

        $clone->value = array_diff_key($clone->value, array_flip($keys));

        return $clone;
    }

    public function get(ValueObject|string $key, mixed $default = null): mixed
    {
        $key = (string) $key;

        return $this->value[$key] ?? $default;
    }

    public function getByKeys(\ADS\ValueObjects\ListValue $keys): static
    {
        $clone = clone $this;

        /** @var array<int|string> $keys */
        $keys = $keys->toArray();

        $clone->value = array_intersect_key($clone->value, array_flip($keys));

        return $clone;
    }

    public function has(ValueObject|string|int $key): bool
    {
        $key = (string) $key;

        return array_key_exists($key, $this->value);
    }

    public function contains(mixed $item): bool
    {
        if ($item instanceof Closure) {
            return ! $this->filter($item)->isEmpty();
        }

        $item = static::toItem($item);
        $identifierClosure = static::itemIdentifier();

        foreach ($this->toItems() as $existingItem) {
            if (
                $existingItem instanceof ValueObject && $existingItem->isEqualTo($item)
                || $identifierClosure($existingItem) === $identifierClosure($item)
                || $existingItem === $item
            ) {
                return true;
            }
        }

        return false;
    }

    public function first(mixed $default = null): mixed
    {
        $first = reset($this->value);

        return $first === false ? $default : $first;
    }

    public function firstKey(int|string|null $default = null): string|int|null
    {
        return array_key_first($this->value);
    }

    public function last(mixed $default = null): mixed
    {
        $reversed = array_reverse($this->value);
        $last = reset($reversed);

        return $last === false ? $default : $last;
    }

    public function lastKey(int|string|null $default = null): string|int|null
    {
        return array_key_last($this->value);
    }

    public function filter(Closure $closure, bool $resetKeys = false): static
    {
        $clone = clone $this;

        $clone->value = array_filter(
            $clone->value,
            $closure
        );

        if ($resetKeys) {
            $clone->value = array_values($clone->value);
        }

        return $clone;
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function implode(string $glue): string
    {
        return implode($glue, array_map(
            static fn ($item) => strval($item),
            $this->toArray()
        ));
    }

    public function unique(?Closure $closure = null): static
    {
        $clone = clone $this;

        $value = $closure !== null ? array_map($closure, $clone->value) : $clone->value;

        $keys = array_keys(array_unique($value));
        $clone->value = array_intersect_key($clone->value, array_flip($keys));

        return $clone;
    }

    private static function checkTypes(mixed ...$values): void
    {
        $type = static::itemType();

        foreach ($values as $item) {
            if (! $item instanceof $type) {
                throw ListException::noValidItemType(
                    self::getType($item),
                    $type,
                    static::class
                );
            }
        }
    }

    private static function toItem(mixed $item): mixed
    {
        try {
            self::checkTypes([$item]);
        } catch (ListException) {
            try {
                $item = static::fromScalarToItem($item);
            } catch (ListException) {
                throw ListException::noValidItemType(
                    self::getType($item),
                    static::itemType(),
                    static::class
                );
            }
        }

        return $item;
    }

    public static function containsType(): ?TypeSchema
    {
        return null;
    }

    public static function minItems(): ?int
    {
        return null;
    }

    public static function maxItems(): ?int
    {
        return null;
    }

    public static function uniqueItems(): ?bool
    {
        return null;
    }

    /**
     * @param string|ValueObject|int $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @param string|ValueObject|int $offset
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->value[(string) $offset];
    }

    /**
     * @param string|ValueObject|int|null $offset
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->put($value, $offset);
    }

    /**
     * @param string|ValueObject|int $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->forget($offset);
    }

    public static function getType(mixed $item): string
    {
        if (is_scalar($item)) {
            return gettype($item);
        }

        if (is_array($item)) {
            return 'array';
        }

        if (is_object($item)) {
            return $item::class;
        }

        throw new RuntimeException(
            sprintf(
                'Type not found for value \'%s\'.',
                strval($item)
            )
        );
    }
}
