<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Implementation\Enum\Exception\NoValidValue;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\IntEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\IntTransitionEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\NoValidEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\NoValidIntEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\NoValidIntTransitionEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\NoValidStringEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\NoValidStringTransitionEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\StringEnum;
use ADS\ValueObjects\Tests\Object\ValueObject\Enum\StringTransitionEnum;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function testStringEnum(): void
    {
        $stringEnum = StringEnum::fromString(StringEnum::TEST_1);
        $stringEnum2 = StringEnum::fromValue(StringEnum::TEST_2);
        $this->assertEquals(StringEnum::TEST_1, (string) $stringEnum);
        $this->assertEquals(StringEnum::TEST_1, $stringEnum->toString());
        $this->assertFalse($stringEnum->isEqualTo($stringEnum2));
        $this->assertFalse($stringEnum->isEqualTo('test'));
    }

    public function testNoValidEnum(): void
    {
        $this->expectExceptionMessageMatches('/has no possible values\./');
        NoValidEnum::fromString('test');
    }

    public function testNoValidStringEnum(): void
    {
        $this->expectExceptionMessageMatches('/must have possible values of the type/');
        NoValidStringEnum::fromString('test2');
    }

    public function testStringEnumNoValidValue(): void
    {
        $this->expectExceptionMessageMatches('/is not valid\. Allowed values/');
        StringEnum::fromString('test');
    }

    public function testIntEnum(): void
    {
        $intEnum = IntEnum::fromInt(IntEnum::TEST_1);
        $intEnum2 = IntEnum::fromValue(IntEnum::TEST_2);
        $this->assertEquals(IntEnum::TEST_1, (string) $intEnum);
        $this->assertEquals(IntEnum::TEST_1, $intEnum->toInt());
        $this->assertFalse($intEnum->isEqualTo($intEnum2));
        $this->assertFalse($intEnum->isEqualTo('test'));
    }

    public function testExampleIntEnum(): void
    {
        $this->assertInstanceOf(IntEnum::class, IntEnum::example());
    }

    public function testRulesIntEnum(): void
    {
        $this->assertEquals(
            ['enum' => [1, 2]],
            IntEnum::validationRules(),
        );
    }

    public function testNoValidIntEnum(): void
    {
        $this->expectExceptionMessageMatches('/must have possible values of the type/');
        NoValidIntEnum::fromInt(1);
    }

    public function testIntEnumNoValidValue(): void
    {
        $this->expectExceptionMessageMatches('/is not valid\. Allowed values/');
        IntEnum::fromInt(32);
    }

    public function testIntTransitionEnum(): void
    {
        $intEnum = IntTransitionEnum::init();
        $this->assertEquals(IntTransitionEnum::TEST_1, $intEnum->toInt());
        $nextIntEnum = $intEnum->next(IntTransitionEnum::fromInt(IntTransitionEnum::TEST_3));
        $this->assertEquals(IntTransitionEnum::TEST_3, $nextIntEnum->toInt());
        $previousIntEnum = $nextIntEnum->previous(IntTransitionEnum::fromInt(IntTransitionEnum::TEST_2));
        $this->assertEquals(IntTransitionEnum::TEST_2, $previousIntEnum->toInt());
    }

    public function testNoValidIntTransitionEnum(): void
    {
        $this->expectExceptionMessageMatches('/must have possible values of the type/');
        NoValidIntTransitionEnum::fromInt(1);
    }

    public function testIntTransitionEnumNoValidValue(): void
    {
        $this->expectExceptionMessageMatches('/is not valid\. Allowed values/');
        IntTransitionEnum::fromInt(32);
    }

    public function testStringTransitionEnum(): void
    {
        $intEnum = StringTransitionEnum::init();
        $this->assertEquals(StringTransitionEnum::TEST_1, $intEnum->toString());
        $nextStringEnum = $intEnum->next(StringTransitionEnum::fromString(StringTransitionEnum::TEST_3));
        $this->assertEquals(StringTransitionEnum::TEST_3, $nextStringEnum->toString());
        $previousStringEnum = $nextStringEnum->previous(StringTransitionEnum::fromString(StringTransitionEnum::TEST_2));
        $this->assertEquals(StringTransitionEnum::TEST_2, $previousStringEnum->toString());
    }

    public function testNoTransitionFoundFromTo(): void
    {
        $this->expectExceptionMessageMatches('/No transition found from value \'.+\' to value/');
        $stringEnum = StringTransitionEnum::fromString(StringTransitionEnum::TEST_3);
        $stringEnum->next(StringTransitionEnum::fromString(StringTransitionEnum::TEST_1));
    }

    public function testNoTransitionFoundFrom(): void
    {
        $this->expectExceptionMessageMatches('/No transition found from value/');
        $stringEnum = StringTransitionEnum::fromString(StringTransitionEnum::TEST_4);
        $stringEnum->next(StringTransitionEnum::fromString(StringTransitionEnum::TEST_1));
    }

    public function testNoReverseTransitionFoundFromTo(): void
    {
        $this->expectExceptionMessageMatches('/No reverse transition found from value \'.+\' to value/');
        $stringEnum = StringTransitionEnum::fromString(StringTransitionEnum::TEST_4);
        $stringEnum->previous(StringTransitionEnum::fromString(StringTransitionEnum::TEST_1));
    }

    public function testNoReverseTransitionFoundFrom(): void
    {
        $this->expectExceptionMessageMatches('/No reverse transition found from value/');
        $stringEnum = StringTransitionEnum::fromString(StringTransitionEnum::TEST_1);
        $stringEnum->previous(StringTransitionEnum::fromString(StringTransitionEnum::TEST_2));
    }

    public function testNoValidStringTransitionEnum(): void
    {
        $this->expectExceptionMessageMatches('/must have possible values of the type/');
        NoValidStringTransitionEnum::fromString('test2');
    }

    public function testStringTransitionEnumNoValidValue(): void
    {
        $this->expectExceptionMessageMatches('/is not valid\. Allowed values/');
        StringTransitionEnum::fromString('lala');
    }

    public function testHttpExceptionForNoValidValue(): void
    {
        try {
            StringTransitionEnum::fromString('lala');
        } catch (NoValidValue $exception) {
            $this->assertEquals(400, $exception->getStatusCode());
        }
    }
}
