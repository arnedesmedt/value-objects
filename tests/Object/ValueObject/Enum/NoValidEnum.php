<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\StringEnumValue;

class NoValidEnum extends StringEnumValue
{
    /** @inheritDoc */
    public static function possibleValues(): array
    {
        return [];
    }
}
