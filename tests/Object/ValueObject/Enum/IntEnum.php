<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\IntEnumValue;
use TeamBlue\ValueObjects\Implementation\ExamplesLogic;

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
