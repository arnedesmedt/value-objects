<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Float\TestFloat;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Int\TestId;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Int\TestInt;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Int\TestRange;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Int\TestRangeExcluded;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Int\TestTimestamp;

class IntTest extends TestCase
{
    public function testInt(): void
    {
        $testInt = TestInt::fromInt(1);
        $testInt2 = TestInt::fromValue(1);
        $this->assertEquals(1, $testInt->toValue());
        $this->assertEquals(1, (string) $testInt2);
        $this->assertTrue($testInt->isEqualTo($testInt2));
        $this->assertFalse($testInt->isEqualTo('test'));
    }

    public function testExampleInt(): void
    {
        $this->assertInstanceOf(TestFloat::class, TestFloat::example());
    }

    public function testRange(): void
    {
        $this->expectExceptionMessageMatches('/has to be lower or equal than 15/');
        $this->assertEquals(15, TestRange::fromInt(15)->toValue());
        TestRange::fromInt(16);
    }

    public function testExampleRange(): void
    {
        $this->assertInstanceOf(TestRange::class, TestRange::example());
    }

    public function testRulesRange(): void
    {
        $this->assertEquals(
            ['minimum' => 0, 'maximum' => 15],
            TestRange::validationRules(),
        );
    }

    public function testRangeExcluded(): void
    {
        $this->expectExceptionMessageMatches('/has to be lower than/');
        TestRangeExcluded::fromInt(1);
    }

    public function testRulesRangeExcluded(): void
    {
        $this->assertEquals(
            ['exclusiveMinimum' => 1, 'exclusiveMaximum' => 15],
            TestRangeExcluded::validationRules(),
        );
    }

    public function testId(): void
    {
        $this->expectExceptionMessageMatches('/has to be lower or equal than/');
        TestId::fromInt(0);
    }

    public function testTimestamp(): void
    {
        $this->expectExceptionMessageMatches('/has to be lower or equal than/');
        TestTimestamp::fromInt(-1);
    }
}
