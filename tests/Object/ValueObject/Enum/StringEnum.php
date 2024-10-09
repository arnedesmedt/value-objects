<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\StringEnumValue;

class StringEnum extends StringEnumValue
{
    final public const TEST_1 = 'test1';
    final public const TEST_2 = 'test2';

    /** @inheritDoc */
    public static function possibleValues(): array
    {
        return [
            self::TEST_1,
            self::TEST_2,
        ];
    }
}
