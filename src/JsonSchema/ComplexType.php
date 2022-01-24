<?php

declare(strict_types=1);

namespace ADS\ValueObjects\JsonSchema;

use EventEngine\JsonSchema\AnnotatedType;
use EventEngine\JsonSchema\Type;
use RuntimeException;

use function addslashes;
use function get_class;
use function method_exists;
use function preg_match;
use function preg_quote;
use function property_exists;
use function sprintf;

final class ComplexType implements AnnotatedType
{
    private Type $originalType;
    /** @var class-string */
    private string $className;

    /**
     * @param class-string $className
     */
    private function __construct(Type $originalType, string $className)
    {
        $this->originalType = $originalType;
        $this->className = $className;
    }

    /**
     * @param array<mixed> $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (! method_exists($this->originalType, $name)) {
            throw new RuntimeException(
                sprintf('Method \'%s\' not found on type \'%s\'.', $name, get_class($this->originalType))
            );
        }

        return $this->originalType->$name(...$arguments);
    }

    public function __isset(string $name): bool
    {
        return property_exists($this->originalType, $name);
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        if (! $this->__isset($name)) {
            throw new RuntimeException(
                sprintf('Property \'%s\' not found on type \'%s\'.', $name, get_class($this->originalType))
            );
        }

        return $this->originalType->toArray()[$name];
    }

    /**
     * @param class-string $class
     */
    public static function fromTypeAndClass(Type $type, string $class): self
    {
        return new self($type, $class);
    }

    /**
     * @return array<string, mixed>
     *
     * @inheritDoc
     */
    public function toArray(): array
    {
        $schema = $this->originalType->toArray();

        if (! preg_match(sprintf('#%s#', preg_quote($_GET['complex'], '#')), $this->className)) {
            return $schema;
        }

        $schema['type'] = '\\' . addslashes($this->className);

        return $schema;
    }

    public function entitled(string $title): self
    {
        if (! $this->originalType instanceof AnnotatedType) {
            return $this;
        }

        $cp = clone $this;

        $cp->originalType = $this->originalType->entitled($title);

        return $cp;
    }

    public function describedAs(string $description): self
    {
        if (! $this->originalType instanceof AnnotatedType) {
            return $this;
        }

        $cp = clone $this;

        $cp->originalType = $this->originalType->describedAs($description);

        return $cp;
    }

    /**
     * @param mixed $default
     */
    public function withDefault($default): self
    {
        if (! $this->originalType instanceof AnnotatedType) {
            return $this;
        }

        $cp = clone $this;

        $cp->originalType = $this->originalType->withDefault($default);

        return $cp;
    }

    /**
     * @param mixed ...$examples
     */
    public function withExamples(...$examples): self
    {
        if (! $this->originalType instanceof AnnotatedType) {
            return $this;
        }

        $cp = clone $this;

        $cp->originalType = $this->originalType->withExamples(...$examples);

        return $cp;
    }

    public function asNullable(): Type
    {
        $cp = clone $this;

        $cp->originalType = $this->originalType->asNullable();

        return $cp;
    }
}
