<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Implementation\TypeDetector;
use ADS\ValueObjects\Tests\Unit\ValueObject\List\TestImmutable;
use ADS\ValueObjects\Tests\Unit\ValueObject\List\TestList;
use ADS\ValueObjects\Tests\Unit\ValueObject\List\TestListImmutable;
use EventEngine\JsonSchema\Type\ArrayType;
use EventEngine\JsonSchema\Type\ObjectType;
use EventEngine\JsonSchema\Type\TypeRef;
use PHPUnit\Framework\TestCase;

class TypeDetectorTest extends TestCase
{
    public function testTypeOfString(): void
    {
        $type = TypeDetector::getTypeFromClass('test');

        $this->assertInstanceOf(TypeRef::class, $type);
    }

    public function testTypeOfClass(): void
    {
        $type = TypeDetector::getTypeFromClass(TestImmutable::class);

        $this->assertInstanceOf(ObjectType::class, $type);
    }

    public function testTypeOfList(): void
    {
        $type = TypeDetector::getTypeFromClass(TestList::class);

        $this->assertInstanceOf(ArrayType::class, $type);
    }

    public function testTypeOfListWithImmutableTypes(): void
    {
        $type = TypeDetector::getTypeFromClass(TestListImmutable::class);

        $this->assertInstanceOf(ArrayType::class, $type);
    }
}
