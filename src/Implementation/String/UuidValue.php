<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class UuidValue implements \ADS\ValueObjects\UuidValue
{
    protected UuidInterface $value;

    protected function __construct(UuidInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @return static
     */
    public static function generate()
    {
        return new static(Uuid::uuid4());
    }

    /**
     * @inheritDoc
     */
    public static function fromString(string $value)
    {
        return new static(Uuid::fromString($value));
    }

    public function toString() : string
    {
        return $this->value->toString();
    }

    public function __toString() : string
    {
        return $this->toString();
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other) : bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->value->equals($other->value);
    }
}
