<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Tests\Unit\ValueObject\Bool\TestBool;
use PHPUnit\Framework\TestCase;

class BoolTest extends TestCase
{
    public function testBool(): void
    {
        $testTrue = TestBool::fromBool(true);
        $testFalse = TestBool::fromValue(false);
        $this->assertTrue($testTrue->toValue());
        $this->assertEquals('false', (string) $testFalse);
        $this->assertFalse($testTrue->isEqualTo($testFalse));
        $this->assertFalse($testTrue->isEqualTo('test'));
    }

    public function testExampleBool(): void
    {
        $this->assertInstanceOf(TestBool::class, TestBool::example());
    }
}
