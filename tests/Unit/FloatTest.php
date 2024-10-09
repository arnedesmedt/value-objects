<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Float\TestFloat;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Float\TestRange;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\Float\TestRangeExcluded;

class FloatTest extends TestCase
{
    public function testFloat(): void
    {
        $testFloat = TestFloat::fromFloat(1.1);
        $testFloat2 = TestFloat::fromValue(1.1);
        $this->assertEquals(1.1, $testFloat->toValue());
        $this->assertEquals(1.1, (string) $testFloat2);
        $this->assertTrue($testFloat->isEqualTo($testFloat2));
        $this->assertFalse($testFloat->isEqualTo('test'));
    }

    public function testExampleFloat(): void
    {
        $this->assertInstanceOf(TestFloat::class, TestFloat::example());
    }

    public function testRange(): void
    {
        $this->expectExceptionMessageMatches('/has to be lower or equal than 10/');
        $this->assertEquals(10.4, TestRange::fromFloat(10.4)->toValue());
        TestRange::fromFloat(10.5);
    }

    public function testExampleRange(): void
    {
        $this->assertInstanceOf(TestRange::class, TestRange::example());
    }

    public function testRulesRange(): void
    {
        $this->assertEquals(
            ['minimum' => 0, 'maximum' => 10.4],
            TestRange::validationRules(),
        );
    }

    public function testRangeExcluded(): void
    {
        $this->expectExceptionMessageMatches('/has to be lower than/');
        TestRangeExcluded::fromFloat(1.2);
    }

    public function testRulesRangeExcluded(): void
    {
        $this->assertEquals(
            ['exclusiveMinimum' => 1.2, 'exclusiveMaximum' => 6.2],
            TestRangeExcluded::validationRules(),
        );
    }
}
