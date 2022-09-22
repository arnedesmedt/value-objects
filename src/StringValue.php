<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface StringValue extends ValueObject
{
    public static function fromString(string $value): static;

    public function toString(): string;
}
