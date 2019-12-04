<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Bool;

use ADS\ValueObjects\BoolValue as BoolValueInterface;

abstract class BoolValue implements BoolValueInterface
{
    protected bool $value;

    protected function __construct(bool $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public static function fromBool(bool $value)
    {
        return new static($value);
    }

    public function toBool() : bool
    {
        return $this->value;
    }

    public function __toString() : string
    {
        return $this->toBool() ? 'true' : 'false';
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo($other) : bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->toBool() === $other->toBool();
    }
}
