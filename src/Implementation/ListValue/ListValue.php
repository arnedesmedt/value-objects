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
use Throwable;

use function array_diff;
use function array_diff_key;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_key_exists;
use function array_key_first;
use function array_key_last;
use function array_map;
use function array_pop;
use function array_push;
use function array_reverse;
use function array_shift;
use function array_unique;
use function array_unshift;
use function array_values;
use function count;
use function implode;
use function is_array;
use function is_object;
use function print_r;
use function reset;
use function sprintf;
use function strval;

/**
 * @template T
 * @implements ArrayAccess<string|int, T>
 * @implements \ADS\ValueObjects\ListValue<T>
 */
abstract class ListValue implements \ADS\ValueObjects\ListValue, JsonSchemaAwareCollection
{
    /** @var array<string|int, T> */
    protected array $value;

    /**
     * @param array<string|int, T> $value
     */
    protected function __construct(array $value)
    {
        $this->value = $value;
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
     */
    private static function __itemType(): string
    {
        return static::itemType();
    }

    private static function __allowNestedSchema(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function fromScalarToItem($value)
    {
        if (is_object($value)) {
            throw ListException::valueIsNotScalar($value);
        }

        $itemType = static::itemType();

        try {
            $relfectionClass = new ReflectionClass($itemType);

            if ($relfectionClass->implementsInterface(ImmutableRecord::class)) {
                /** @var T $item */
                $item = $itemType::fromArray($value);

                return $item;
            }

            if ($relfectionClass->implementsInterface(ValueObject::class)) {
                /** @var T $item */
                $item = $itemType::fromValue($value);

                return $item;
            }

            throw ListException::fromScalarToItemNotImplemented(static::class);
        } catch (ReflectionException $exception) {
            throw ListException::itemTypeNotFound($itemType, static::class);
        }
    }

    /**
     * @inheritDoc
     */
    public static function fromItemToScalar($item)
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
        } catch (ReflectionException $exception) {
            throw ListException::itemTypeNotFound($itemType, static::class);
        }
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $value)
    {
        return static::fromItems(array_map(
            static fn ($array) => static::fromScalarToItem($array),
            $value
        ));
    }

    /**
     * @inheritDoc
     */
    public static function fromItems(array $values)
    {
        self::checkTypes(...$values);

        return new static($values);
    }

    /**
     * @inheritDoc
     */
    public static function emptyList()
    {
        return new static([]);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_map(
            static fn ($item) => static::fromItemToScalar($item),
            $this->value
        );
    }

    /**
     * @inheritDoc
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
     * @return static<T>
     *
     * @inheritDoc
     */
    public static function fromValue($value)
    {
        if (! is_array($value)) {
            throw new RuntimeException('No array given.');
        }

        /** @var static<T> $result */
        $result = static::fromArray($value);

        return $result;
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

    /**
     * @inheritDoc
     */
    public function push($item)
    {
        $clone = clone $this;

        array_push($clone->value, static::toItem($item));

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function put($item, $key = null)
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

    /**
     * @inheritDoc
     */
    public function pop()
    {
        $clone = clone $this;

        array_pop($clone->value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function shift()
    {
        $clone = clone $this;

        array_shift($clone->value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function unshift($item)
    {
        $clone = clone $this;

        array_unshift($clone->value, static::toItem($item));

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function forget($key)
    {
        $clone = clone $this;

        $key = (string) $key;

        unset($clone->value[$key]);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function diffByKeys(\ADS\ValueObjects\ListValue $keys)
    {
        $clone = clone $this;

        /** @var array<string|int> $keyValues */
        $keyValues = $keys->toArray();

        $clone->value = array_diff_key($clone->value, array_flip($keyValues));

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $key = (string) $key;

        return $this->value[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getByKeys(\ADS\ValueObjects\ListValue $keys)
    {
        $clone = clone $this;

        /** @var array<string|int> $keyValues */
        $keyValues = $keys->toArray();

        $clone->value = array_intersect_key($clone->value, array_flip($keyValues));

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        $key = (string) $key;

        return array_key_exists($key, $this->value);
    }

    /**
     * @inheritDoc
     */
    public function contains($item): bool
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

    /**
     * @inheritDoc
     */
    public function keyByItem($item, $default = null)
    {
        $item = static::toItem($item);
        $identifierClosure = static::itemIdentifier();

        foreach ($this->toItems() as $key => $existingItem) {
            if (
                $existingItem instanceof ValueObject && $existingItem->isEqualTo($item)
                || $identifierClosure($existingItem) === $identifierClosure($item)
                || $existingItem === $item
            ) {
                return $key;
            }
        }

        return $default;
    }

    /**
     * @inheritDoc
     */
    public function first($default = null)
    {
        $first = reset($this->value);

        return $first === false ? $default : $first;
    }

    /**
     * @inheritDoc
     */
    public function needFirst(?Throwable $exception = null)
    {
        $first = reset($this->value);

        if ($first === false) {
            throw $exception ?? new RuntimeException(
                sprintf('No first value found for list \'%s\'.', static::class)
            );
        }

        return $first;
    }

    /**
     * @inheritDoc
     */
    public function firstKey($default = null)
    {
        return array_key_first($this->value);
    }

    /**
     * @inheritDoc
     */
    public function last($default = null)
    {
        $reversed = array_reverse($this->value);
        $last = reset($reversed);

        return $last === false ? $default : $last;
    }

    /**
     * @inheritDoc
     */
    public function needLast(?Throwable $exception = null)
    {
        $reversed = array_reverse($this->value);
        $last = reset($reversed);

        if ($last === false) {
            throw $exception ?? new RuntimeException(
                sprintf('No last value found for list \'%s\'.', static::class)
            );
        }

        return $last;
    }

    /**
     * @inheritDoc
     */
    public function lastKey($default = null)
    {
        return array_key_last($this->value);
    }

    /**
     * @inheritDoc
     */
    public function filter(Closure $closure, bool $resetKeys = false)
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

    /**
     * @inheritDoc
     */
    public function map(Closure $closure)
    {
        $clone = clone $this;

        $clone->value = array_map(
            $closure,
            $clone->value
        );

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

    /**
     * @inheritDoc
     */
    public function unique()
    {
        return self::fromArray(array_unique($this->toArray()));
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
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->value[strval($offset)];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->put($value, $offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        $this->forget($offset);
    }

    /**
     * @param T $item
     *
     * @return T
     */
    private static function toItem($item)
    {
        try {
            self::checkTypes($item);
        } catch (ListException $exception) {
            try {
                $item = static::fromScalarToItem($item);
            } catch (ListException $exception) {
                throw ListException::noValidItemType(
                    $item,
                    static::itemType(),
                    static::class
                );
            }
        }

        return $item;
    }

    /**
     * @param mixed ...$values
     */
    private static function checkTypes(...$values): void
    {
        $type = static::itemType();

        foreach ($values as $item) {
            if (! $item instanceof $type) {
                throw ListException::noValidItemType(
                    $item,
                    $type,
                    static::class
                );
            }
        }
    }
}
