<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\String;

use TeamBlue\ValueObjects\Implementation\String\PatternValue;

class TestPattern extends PatternValue
{
    public static function pattern(): string
    {
        return '^[a-z0-9][a-zA-Z0-9-_]+$';
    }
}
