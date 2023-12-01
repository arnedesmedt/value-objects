<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Object\ValueObject\Enum;

use ADS\ValueObjects\Implementation\Enum\IntEnumValue;
use ADS\ValueObjects\Implementation\ExamplesLogic;

class IntEnum extends IntEnumValue
{
    use ExamplesLogic;

    final public const TEST_1 = 1;
    final public const TEST_2 = 2;

    /** @inheritDoc */
    public static function possibleValues(): array
    {
        return [
            self::TEST_1,
            self::TEST_2,
        ];
    }
}
