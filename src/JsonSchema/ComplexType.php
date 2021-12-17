<?php

declare(strict_types=1);

namespace ADS\ValueObjects\JsonSchema;

use EventEngine\JsonSchema\AnnotatedType;
use EventEngine\JsonSchema\Type;
use RuntimeException;

use function addslashes;
use function method_exists;
use function preg_match;
use function preg_quote;
use function property_exists;
use function sprintf;

final class ComplexType implements AnnotatedType
{
    private readonly Type $originalType;

    /**
     * @param class-string $className
     */
    private function __construct(Type $type, private readonly string $className)
    {
        $this->originalType = $type;
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (! method_exists($this->originalType, $name)) {
            throw new RuntimeException(
                sprintf('Method \'%s\' not found on type \'%s\'.', $name, $this->originalType::class)
            );
        }

        return $this->originalType->$name(...$arguments);
    }

    public function __isset(string $name): bool
    {
        return property_exists($this->originalType, $name);
    }

    public function __get(string $name): mixed
    {
        if (! $this->__isset($name)) {
            throw new RuntimeException(
                sprintf('Property \'%s\' not found on type \'%s\'.', $name, $this->originalType::class)
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

    public function withDefault(mixed $default): self
    {
        if (! $this->originalType instanceof AnnotatedType) {
            return $this;
        }

        $cp = clone $this;

        $cp->originalType = $this->originalType->withDefault($default);

        return $cp;
    }

    public function withExamples(mixed ...$examples): self
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
