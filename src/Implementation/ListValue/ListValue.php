<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ADS\ValueObjects\Exception\ListException;
use ADS\ValueObjects\HasExamples;
use ADS\ValueObjects\Implementation\ExamplesLogic;
use ADS\ValueObjects\ValueObject;
use Closure;
use EventEngine\Data\ImmutableRecord;
use EventEngine\JsonSchema\JsonSchemaAwareCollection;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\ArrayType;
use EventEngine\Schema\TypeSchema;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Stringable;
use Throwable;

use function array_diff;
use function array_diff_key;
use function array_filter;
use function array_flip;
use function array_intersect;
use function array_intersect_key;
use function array_key_exists;
use function array_key_first;
use function array_key_last;
use function array_keys;
use function array_map;
use function array_merge;
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
use function json_encode;
use function reset;
use function sprintf;
use function strval;
use function usort;

use const JSON_THROW_ON_ERROR;

/**
 * @template T of object
 * @template-implements \ADS\ValueObjects\ListValue<T>
 * @phpstan-consistent-constructor
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class ListValue implements
    \ADS\ValueObjects\ListValue,
    JsonSchemaAwareCollection,
    HasExamples,
    ProvidesValidationRules,
    Stringable
{
    use ExamplesLogic;

    /** @param array<string|int, T> $value */
    protected function __construct(protected array $value)
    {
    }

    public static function itemIdentifier(): Closure
    {
        return static function ($item) {
            if ($item instanceof ValueObject) {
                return $item->__toString();
            }

            throw ListException::noItemIdentifierFound(
                static::class,
                static::itemType(),
            );
        };
    }

    /** @return class-string */
    private static function __itemType(): string
    {
        return static::itemType();
    }

    private static function __allowNestedSchema(): bool
    {
        return true;
    }

    /** @inheritDoc */
    public static function fromScalarToItem(mixed $value)
    {
        if (is_object($value)) {
            throw ListException::valueIsNotScalar($value);
        }

        /** @var class-string<ImmutableRecord|ValueObject> $itemType */
        $itemType = static::itemType();

        try {
            $relfectionClass = new ReflectionClass($itemType);

            if ($relfectionClass->implementsInterface(ImmutableRecord::class) && is_array($value)) {
                /** @var class-string<ImmutableRecord> $type */
                $type = $itemType;
                /** @var T $item */
                $item = $type::fromArray($value);

                return $item;
            }

            if ($relfectionClass->implementsInterface(ValueObject::class)) {
                /** @var class-string<ValueObject> $type */
                $type = $itemType;
                /** @var T $item */
                $item = $type::fromValue($value);

                return $item;
            }

            throw ListException::fromScalarToItemNotImplemented(static::class);
        } catch (ReflectionException) {
            throw ListException::itemTypeNotFound($itemType, static::class);
        }
    }

    /** @inheritDoc */
    public static function fromItemToScalar($item): mixed
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

    /** @inheritDoc */
    public static function fromArray(array $value): static
    {
        return static::fromItems(array_map(
            static fn ($array) => static::fromScalarToItem($array),
            $value,
        ));
    }

    /** @inheritDoc */
    public static function fromItems(array $values): static
    {
        self::checkTypes($values);

        return new static($values);
    }

    public static function emptyList(): static
    {
        return new static([]);
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return array_map(
            static fn ($item) => static::fromItemToScalar($item),
            $this->value,
        );
    }

    /** @inheritDoc */
    public function toItems(): array
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR, 512);
    }

    public function toValue(): mixed
    {
        return $this->toArray();
    }

    public static function fromValue(mixed $value): static
    {
        if (! is_array($value)) {
            throw new RuntimeException('No array given.');
        }

        return static::fromArray($value);
    }

    public function isEqualTo(mixed $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return empty(
            array_diff(
                $this->toArray(),
                $other->toArray(),
            )
        )
            && empty(
                array_diff(
                    $other->toArray(),
                    $this->toArray(),
                )
            );
    }

    public function push(mixed $item): static
    {
        $clone = clone $this;

        array_push($clone->value, self::toItem($item));

        return $clone;
    }

    public function put(mixed $item, ValueObject|string|int|null $key = null): static
    {
        $clone = clone $this;
        $item = self::toItem($item);

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

        array_unshift($clone->value, self::toItem($item));

        return $clone;
    }

    public function forget(ValueObject|string|int $key): static
    {
        $clone = clone $this;

        $key = (string) $key;

        unset($clone->value[$key]);

        return $clone;
    }

    /** @inheritDoc */
    public function keys(): array
    {
        return array_keys($this->value);
    }

    public function diffByKeys(\ADS\ValueObjects\ListValue|array $keys): static
    {
        $clone = clone $this;

        /** @var array<string|int> $keyValues */
        $keyValues = $keys instanceof ListValue
            ? $keys->toArray()
            : $keys;

        $clone->value = array_diff_key($clone->value, array_flip($keyValues));

        return $clone;
    }

    /** @inheritDoc */
    public function get(ValueObject|string|int $key, $default = null)
    {
        $key = (string) $key;

        return $this->value[$key] ?? $default;
    }

    /** @inheritDoc */
    public function need(ValueObject|string|int $key, Throwable|null $exception = null)
    {
        $item = $this->get($key);

        if ($item === null) {
            throw $exception ?? new RuntimeException(
                sprintf(
                    'No item found for key \'%s\' in list \'%s\'.',
                    strval($key),
                    static::class,
                ),
            );
        }

        return $item;
    }

    public function getByKeys(\ADS\ValueObjects\ListValue|array $keys): static
    {
        $clone = clone $this;

        /** @var array<string|int> $keyValues */
        $keyValues = $keys instanceof ListValue
            ? $keys->toArray()
            : $keys;

        $clone->value = array_intersect_key($clone->value, array_flip($keyValues));

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

        $item = self::toItem($item);
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

    public function keyByItem(mixed $item, string|int|null $default = null): string|int|null
    {
        $item = self::toItem($item);
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

    public function needKey(mixed $item, Throwable|null $exception = null): string|int
    {
        $key = $this->keyByItem($item);

        if ($key === null) {
            throw $exception ?? new RuntimeException(
                sprintf(
                    'No key found for item \'%s\' in list \'%s\'.',
                    strval($item),
                    static::class,
                ),
            );
        }

        return $key;
    }

    /** @inheritDoc */
    public function first($default = null)
    {
        $first = reset($this->value);

        return $first === false ? $default : $first;
    }

    /** @inheritDoc */
    public function needFirst(Throwable|null $exception = null)
    {
        $first = reset($this->value);

        if ($first === false) {
            throw $exception ?? new RuntimeException(
                sprintf('No first value found for list \'%s\'.', static::class),
            );
        }

        return $first;
    }

    public function firstKey(string|int|null $default = null): string|int|null
    {
        return array_key_first($this->value) ?? $default;
    }

    /** @inheritDoc */
    public function last($default = null)
    {
        $reversed = array_reverse($this->value);
        $last = reset($reversed);

        return $last === false ? $default : $last;
    }

    /** @inheritDoc */
    public function needLast(Throwable|null $exception = null)
    {
        $reversed = array_reverse($this->value);
        $last = reset($reversed);

        if ($last === false) {
            throw $exception ?? new RuntimeException(
                sprintf('No last value found for list \'%s\'.', static::class),
            );
        }

        return $last;
    }

    public function lastKey(string|int|null $default = null): string|int|null
    {
        return array_key_last($this->value) ?? $default;
    }

    public function filter(Closure $closure): static
    {
        $clone = clone $this;

        $clone->value = array_filter(
            $clone->value,
            $closure,
        );

        return $clone;
    }

    public function map(Closure $closure): static
    {
        return self::fromItems(
            array_map(
                static fn ($value) => self::toItem($closure($value)),
                $this->value,
            ),
        );
    }

    /** @inheritDoc */
    public function merge($list): static
    {
        return self::fromItems(
            array_merge(
                $this->value,
                $list->toItems(),
            ),
        );
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
            $this->toArray(),
        ));
    }

    public function intersect(\ADS\ValueObjects\ListValue $list): static
    {
        return static::fromArray(array_intersect($this->toArray(), $list->toArray()));
    }

    public function values(): static
    {
        return static::fromArray(array_values($this->toArray()));
    }

    public function unique(): static
    {
        if ($this->first() instanceof Stringable) {
            return static::fromItems(array_unique($this->toItems()));
        }

        return self::fromArray(array_unique($this->toArray()));
    }

    public static function containsType(): TypeSchema|null
    {
        return null;
    }

    public static function minItems(): int|null
    {
        return null;
    }

    public static function maxItems(): int|null
    {
        return null;
    }

    public static function uniqueItems(): bool|null
    {
        return null;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->value[strval($offset)];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->put($value, $offset);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->forget($offset);
    }

    public function usort(Closure $closure): static
    {
        $clone = clone $this;

        usort($clone->value, $closure);

        return $clone;
    }

    /** @return T */
    private static function toItem(mixed $item)
    {
        try {
            self::checkTypes([$item]);
        } catch (ListException) {
            try {
                $item = static::fromScalarToItem($item);
            } catch (ListException) {
                throw ListException::noValidItemType(
                    $item,
                    static::itemType(),
                    static::class,
                );
            }
        }

        /** @var T $result */
        $result = $item;

        return $result;
    }

    /** @param array<mixed> $values */
    private static function checkTypes(array $values): void
    {
        $type = static::itemType();

        foreach ($values as $item) {
            if (! $item instanceof $type) {
                throw ListException::noValidItemType(
                    $item,
                    $type,
                    static::class,
                );
            }
        }
    }

    public static function example(): static|null
    {
        /** @var class-string<HasExamples> $itemType */
        $itemType = static::itemType();
        $reflectionItem = new ReflectionClass($itemType);

        if (! $reflectionItem->implementsInterface(HasExamples::class)) {
            return null;
        }

        /** @var array<T> $items */
        $items = [
            $itemType::example(),
        ];

        return static::fromItems($items);
    }

    /** @return array<string, mixed>  */
    public static function validationRules(): array
    {
        $rules = [
            ArrayType::MIN_ITEMS => static::minItems(),
            ArrayType::MAX_ITEMS => static::maxItems(),
            ArrayType::UNIQUE_ITEMS => static::uniqueItems(),
        ];

        return array_filter($rules, static fn ($rule) => $rule !== null);
    }
}
