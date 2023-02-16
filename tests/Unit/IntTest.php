<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Tests\Unit\ValueObject\Float\TestFloat;
use ADS\ValueObjects\Tests\Unit\ValueObject\Int\TestId;
use ADS\ValueObjects\Tests\Unit\ValueObject\Int\TestInt;
use ADS\ValueObjects\Tests\Unit\ValueObject\Int\TestRange;
use ADS\ValueObjects\Tests\Unit\ValueObject\Int\TestRangeExcluded;
use ADS\ValueObjects\Tests\Unit\ValueObject\Int\TestTimestamp;
use PHPUnit\Framework\TestCase;

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
        $this->expectExceptionMessageMatches('/has to be lower or equal than 10/');
        $this->assertEquals(10, TestRange::fromInt(10)->toValue());
        TestRange::fromInt(11);
    }

    public function testExampleRange(): void
    {
        $this->assertInstanceOf(TestRange::class, TestRange::example());
    }

    public function testRulesRange(): void
    {
        $this->assertEquals(
            ['minimum' => 0, 'maximum' => 10],
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
