<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Enum;

use TeamBlue\ValueObjects\Implementation\Enum\StringTransitionEnumValue;

class StringTransitionEnum extends StringTransitionEnumValue
{
    final public const TEST_1 = 'test1';
    final public const TEST_2 = 'test2';
    final public const TEST_3 = 'test3';
    final public const TEST_4 = 'test4';

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
        ];
    }

    public static function init(): static
    {
        return self::fromString(self::TEST_1);
    }
}
