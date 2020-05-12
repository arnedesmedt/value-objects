<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\ListValue;

use ADS\ValueObjects\Exception\ListException;
use ADS\ValueObjects\ValueObject;
use Closure;
use EventEngine\Data\ImmutableRecord;
use EventEngine\JsonSchema\JsonSchemaAwareCollection;
use EventEngine\JsonSchema\JsonSchemaAwareCollectionLogic;
use ReflectionClass;
use ReflectionException;
use function array_diff;
use function array_filter;
use function array_flip;
use function array_intersect_key;
use function array_key_exists;
use function array_keys;
use function array_map;
use function array_pop;
use function array_push;
use function array_reverse;
use function array_unique;
use function count;
use function get_class;
use function gettype;
use function implode;
use function is_array;
use function is_scalar;
use function print_r;
use function reset;

abstract class ListValue implements \ADS\ValueObjects\ListValue, JsonSchemaAwareCollection
{
    use JsonSchemaAwareCollectionLogic;

    /** @var mixed[] */
    protected array $value;

    /**
     * @param array<mixed> $values
     */
    protected function __construct(array $values)
    {
        $this->value = $values;
    }

    public static function itemIdentifier() : Closure
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
     * @phpcsSuppress SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
     */
    private static function __itemType() : string
    {
        return static::itemType();
    }

    // phpcs:ignore SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
    private static function __allowNestedSchema() : bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public static function fromScalarToItem($value)
    {
        $itemType = static::itemType();

        try {
            $relfectionClass = new ReflectionClass($itemType);

            if ($relfectionClass->implementsInterface(ImmutableRecord::class)) {
                return $itemType::fromArray($value);
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

            if ($relfectionClass->implementsInterface(ImmutableRecord::class)) {
                return $item->toArray();
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
        self::checkTypes($values);

        return new static($values);
    }

    /**
     * @inheritDoc
     */
    public static function emptyList()
    {
        return new static();
    }

    /**
     * @return array<mixed>
     */
    public function toArray() : array
    {
        return array_map(
            static fn($item) => static::fromItemToScalar($item),
            $this->value
        );
    }

    /**
     * @return array<mixed>
     */
    public function toItems() : array
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return print_r($this->toArray(), true);
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other) : bool
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
    public function get($key, $default = null)
    {
        $key = (string) $key;

        return $this->value[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function has($key) : bool
    {
        $key = (string) $key;

        return array_key_exists($key, $this->value);
    }

    /**
     * @inheritDoc
     */
    public function contains($item) : bool
    {
        if ($item instanceof Closure) {
            return ! $this->filter($item)->isEmpty();
        }

        $item = static::toItem($item);
        $identifierClosure = self::itemIdentifier();

        foreach ($this->toItems() as $existingItem) {
            if ($existingItem instanceof ValueObject && $existingItem->isEqualTo($item)
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
    public function first($default = null)
    {
        $first = reset($this->value);

        return $first === false ? $default : $first;
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
    public function filter(Closure $closure)
    {
        $clone = clone $this;

        $clone->value = array_filter(
            $clone->value,
            $closure
        );

        return $clone;
    }

    public function count() : int
    {
        return count($this->value);
    }

    public function isEmpty() : bool
    {
        return empty($this->value);
    }

    public function implode(string $glue) : string
    {
        return implode($glue, array_map(
            static function ($item) {
                return (string) $item;
            },
            $this->toArray()
        ));
    }

    /**
     * @inheritDoc
     */
    public function unique(?Closure $closure = null)
    {
        $clone = clone $this;

        $value = $closure !== null ? array_map($closure, $clone->value) : $clone->value;

        $keys = array_keys(array_unique($value));
        $clone->value = array_intersect_key($clone->value, array_flip($keys));

        return $clone;
    }

    /**
     * @param mixed $values
     */
    private static function checkTypes($values) : void
    {
        $type = static::itemType();

        foreach ($values as $item) {
            if (! $item instanceof $type) {
                $givenType = is_scalar($item) ? gettype($item) : (is_array($item) ? 'array' : get_class($item));

                throw ListException::noValidItemType(
                    $givenType,
                    $type,
                    static::class
                );
            }
        }
    }

    /**
     * @param mixed $item
     *
     * @return mixed
     */
    private static function toItem($item)
    {
        try {
            self::checkTypes([$item]);
        } catch (ListException $exception) {
            $item = static::fromScalarToItem($item);
        }

        return $item;
    }
}
