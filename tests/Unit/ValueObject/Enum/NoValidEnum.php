<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit\ValueObject\Enum;

use ADS\ValueObjects\Implementation\Enum\StringEnumValue;

class NoValidEnum extends StringEnumValue
{
    /** @inheritDoc */
    public static function possibleValues(): array
    {
        return [];
    }
}
