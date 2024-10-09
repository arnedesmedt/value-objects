<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

interface PatternValue extends StringValue
{
    public static function pattern(): string;
}
