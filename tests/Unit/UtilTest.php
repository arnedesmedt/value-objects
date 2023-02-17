<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Tests\Unit\ValueObject\List\TestImmutable;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestString;
use ADS\ValueObjects\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testToScalar(): void
    {
        $immutable = TestImmutable::fromArray(['test' => 'test']);
        $valueObject = TestString::fromString('string');
        $scalar = true;

        $this->assertEquals(['test' => 'test'], Util::toScalar($immutable));
        $this->assertEquals('string', Util::toScalar($valueObject));
        $this->assertTrue(Util::toScalar($scalar));
    }
}
