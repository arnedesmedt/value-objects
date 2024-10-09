<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\IntTransitionEnumValue;

class IntTransitionEnum extends IntTransitionEnumValue
{
    final public const TEST_1 = 1;
    final public const TEST_2 = 2;
    final public const TEST_3 = 3;
    final public const TEST_4 = 4;

    /** @inheritDoc */
    public static function possibleValues(): array
    {
        return [
            self::TEST_1,
            self::TEST_2,
            self::TEST_3,
            self::TEST_4,
        ];
    }

    /** @inheritDoc */
    public static function transitions(): array
    {
        return [
            self::TEST_1 => [self::TEST_2, self::TEST_3],
            self::TEST_2 => [self::TEST_3, self::TEST_4],
            self::TEST_3 => [self::TEST_4],
            self::TEST_4 => [self::TEST_1],
        ];
    }

    public static function init(): static
    {
        return self::fromInt(self::TEST_1);
    }
}
