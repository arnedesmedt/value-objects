<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface BoolValue extends ValueObject
{
    /**
     * @return static
     */
    public static function fromBool(bool $value): static;

    public function toBool(): bool;
}
