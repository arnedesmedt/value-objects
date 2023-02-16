<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Tests\Unit\ValueObject\Int\TestInt;
use ADS\ValueObjects\Tests\Unit\ValueObject\Int\TestIntWithCalcValue;
use PHPUnit\Framework\TestCase;

class CalcTest extends TestCase
{
    public function testCalc(): void
    {
        $testInt = TestIntWithCalcValue::fromInt(3);
        $testInt2 = TestIntWithCalcValue::fromInt(6);
        $this->assertEquals(9, $testInt->add($testInt2)->toInt());
        $this->assertEquals(5, $testInt->add(2)->toInt());
        $this->assertEquals(-3, $testInt->substract($testInt2)->toInt());
        $this->assertEquals(2, $testInt->substract(1)->toInt());
        $this->assertEquals(18, $testInt->multiply($testInt2)->toInt());
        $this->assertEquals(2, $testInt2->divide($testInt)->toInt());
        $this->assertTrue($testInt->isLowerThan($testInt2));
        $this->assertFalse($testInt->isGreaterThan($testInt2));
        $this->assertTrue($testInt->isLowerOrEqualThan(TestIntWithCalcValue::fromInt(3)));
        $this->assertTrue($testInt->isGreaterOrEqualThan(TestIntWithCalcValue::fromInt(3)));
        $this->assertEquals(6 * 6 * 6, $testInt2->pow($testInt)->toInt());
        $this->assertEquals(
            2,
            TestIntWithCalcValue::fromInt(4)
                ->square(TestIntWithCalcValue::fromInt(2))
                ->toInt(),
        );
    }

    public function testNotSameInstance(): void
    {
        $this->expectExceptionMessageMatches('/should be an instance of /');
        $testInt = TestIntWithCalcValue::fromInt(1);
        $testInt->add(TestInt::fromInt(1));
    }

    public function testDivideByZero(): void
    {
        $this->expectExceptionMessageMatches('/Can\'t divide by zero/');
        $testInt = TestIntWithCalcValue::fromInt(1);
        $testInt->divide(TestIntWithCalcValue::fromInt(0));
    }

    public function testDivideToFloat(): void
    {
        $this->expectExceptionMessageMatches('/Result can\'t be a float for value object/');
        $testInt = TestIntWithCalcValue::fromInt(5);
        $this->assertEquals(2.5, $testInt->divide(TestIntWithCalcValue::fromInt(2))->toInt());
    }
}
