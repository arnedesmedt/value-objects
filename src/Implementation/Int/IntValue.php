<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use ADS\ValueObjects\IntValue as IntValueInterface;

abstract class IntValue implements IntValueInterface
{
    protected int $value;

    protected function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public static function fromInt(int $value)
    {
        return new static($value);
    }

    public function toInt() : int
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return '' . $this->toInt();
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other) : bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toInt() === $other->toInt();
    }
}
