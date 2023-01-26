<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Tests\Unit\ValueObject\List\TestImmutable;
use ADS\ValueObjects\Tests\Unit\ValueObject\List\TestList;
use ADS\ValueObjects\Tests\Unit\ValueObject\List\TestListImmutable;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestEmail;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestString;
use EventEngine\Schema\TypeSchema;
use PHPUnit\Framework\TestCase;

class ListTest extends TestCase
{
    public function testList(): void
    {
        $list = TestList::fromArray(['test', 'test2']);
        $list2 = TestList::fromValue(['test', 'test2']);
        $list3 = TestList::fromItems([TestString::fromString('test0'), TestString::fromString('test2')]);

        $this->assertEquals(['test', 'test2'], $list->toArray());
        $this->assertEquals(['test', 'test2'], $list->toValue());
        $this->assertInstanceOf(TestString::class, $list->first());
        $this->assertInstanceOf(TestString::class, $list->last());
        $this->assertInstanceOf(TestString::class, $list->get(0));
        $this->assertInstanceOf(TestString::class, $list->toItems()[0]);
        $this->assertTrue($list->isEqualTo($list2));
        $this->assertFalse($list->isEqualTo($list3));
        $this->assertFalse($list->isEqualTo('test'));

        $list3 = $list3->shift();
        $list3 = $list3->unshift('test');
        $this->assertTrue($list->isEqualTo($list3));
        $list = $list->push(TestString::fromString('test3'));
        $this->assertEquals(['test', 'test2', 'test3'], $list->toArray());
        $list = $list->pop();
        $this->assertEquals(['test', 'test2'], $list->toArray());
        $this->assertEquals(2, $list->count());
        $this->assertEquals('test, test2', $list->implode(', '));
        $this->assertEquals(['test', 'test2', 'test3'], $list->merge(TestList::fromArray(['test3']))->toArray());
        $this->assertEquals('test3', $list->put('test3')->last());
        $this->assertEquals('test3', $list->put('test3', 0)->first());
        $this->assertEquals('test2', $list->forget(0)->first());

        $this->assertInstanceOf(TypeSchema::class, TestList::__itemSchema());
    }

    public function testListImmutable(): void
    {
        $list = TestListImmutable::fromArray(
            [
                ['test' => 'test'],
                ['test' => 'test2'],
            ]
        );

        $this->assertInstanceOf(TestImmutable::class, $list->first());
    }

    public function testListWithKeys(): void
    {
        $list = TestList::fromArray(['test' => 'test', 'test2' => 'test2']);
        $this->assertEquals('test', $list->keyByItem(TestString::fromString('test')));
        $this->assertNull($list->keyByItem('test3'));
    }

    public function testListWithDifferentTypes(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid list item type/');
        $list = TestList::fromItems(
            [
                TestString::fromString('test'),
                TestString::fromString('test2'),
                TestEmail::fromString('arne@arne.be'),
            ]
        );
    }

    public function testEmptyList(): void
    {
        $emptyList = TestList::emptyList();

        $this->assertEmpty($emptyList->toArray());
        $this->assertTrue($emptyList->isEmpty());
        $this->assertEquals("Array\n(\n)\n", (string) $emptyList);
        $this->assertEquals(2, $emptyList->first(2));
        $this->assertEquals(2, $emptyList->last(2));
        $this->assertEquals(2, $emptyList->lastKey(2));
        $this->assertEquals(2, $emptyList->firstKey(2));
    }

    public function testListNeedsAnArray(): void
    {
        $this->expectExceptionMessageMatches('/No array given/');
        TestList::fromValue('test');
    }
}
