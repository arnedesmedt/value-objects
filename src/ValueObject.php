<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface ValueObject
{
    public function __toString(): string;

    public function isEqualTo(mixed $other): bool;

    public function toValue(): mixed;

    public static function fromValue(mixed $value): static;
}
