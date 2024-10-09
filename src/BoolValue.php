<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

interface BoolValue extends ValueObject
{
    public static function fromBool(bool $value): static;

    public function toBool(): bool;
}
