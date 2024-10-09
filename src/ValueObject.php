<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

interface ValueObject
{
    public function __toString(): string;

    public function isEqualTo(mixed $other): bool;

    public function toValue(): mixed;

    public static function fromValue(mixed $value): static;
}
