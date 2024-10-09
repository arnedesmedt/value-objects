<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Unit;

use EventEngine\Schema\TypeSchema;
use PHPUnit\Framework\TestCase;
use TeamBlue\ValueObjects\Tests\Object\Immutable\TestImmutable;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\List\TestList;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\List\TestListImmutable;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\String\TestEmail;
use TeamBlue\ValueObjects\Tests\Object\ValueObject\String\TestString;

use function str_replace;
use function str_starts_with;
use function usort;

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
        $this->assertInstanceOf(TestString::class, $list->need(0));
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
        $this->assertTrue($list->contains('test2'));
        $this->assertFalse($list->contains('test3'));
        $this->assertTrue($list->contains(static fn (string $test) => str_starts_with($test, 'tes')));
        $this->assertEquals([1 => 'test2'], $list->filter(static fn (string $test) => $test === 'test2')->toArray());
        $this->assertEquals(
            ['', '2'],
            $list->map(static fn (TestString $test) => str_replace('test', '', $test->toString()))->toArray(),
        );
        $this->assertEquals(
            [1 => 'test2'],
            $list->intersect(TestList::fromArray(['test0', 'test2']))->toArray(),
        );

        $this->assertEquals(
            ['test', 2 => 'test2'],
            TestList::fromArray(['test', 'test', 'test2'])->unique()->toArray(),
        );

        $this->assertInstanceOf(TypeSchema::class, TestList::__itemSchema());
    }

    public function testExampleList(): void
    {
        $this->assertInstanceOf(TestList::class, TestList::example());
    }

    public function testRulesList(): void
    {
        $this->assertEquals(
            [
                'minItems' => 1,
                'maxItems' => 5,
                'uniqueItems' => true,
            ],
            TestList::validationRules(),
        );
    }

    public function testNeedByKey(): void
    {
        $this->expectExceptionMessageMatches('/No item found for key/');
        $list = TestList::fromArray(['test', 'test2']);
        $list->need(3);
    }

    public function testNeedFirst(): void
    {
        $this->expectExceptionMessageMatches('/No first value found for list/');
        $list = TestList::emptyList();
        $list->needFirst();
    }

    public function testNeedLast(): void
    {
        $this->expectExceptionMessageMatches('/No last value found for list/');
        $list = TestList::emptyList();
        $list->needLast();
    }

    public function testListImmutable(): void
    {
        $list = TestListImmutable::fromArray(
            [
                ['test' => 'test'],
                ['test' => 'test2'],
            ],
        );

        $this->assertInstanceOf(TestImmutable::class, $list->needFirst());
    }

    public function testListWithKeys(): void
    {
        $list = TestList::fromArray(['test' => 'test', 'test2' => 'test2']);
        $keysList = TestList::fromArray(['test']);
        $this->assertEquals('test', $list->needKey(TestString::fromString('test')));
        $this->assertNull($list->keyByItem('test3'));
        $this->assertEquals(['test', 'test2'], $list->keys());
        $this->assertEquals(['test2' => 'test2'], $list->diffByKeys($keysList)->toArray()); // @phpstan-ignore-line
        $this->assertEquals(['test' => 'test'], $list->getByKeys($keysList)->toArray()); // @phpstan-ignore-line
        $this->assertEquals(['test2' => 'test2'], $list->diffByKeys(['test'])->toArray());
        $this->assertEquals(['test' => 'test'], $list->getByKeys(['test'])->toArray());
        $this->assertTrue($list->has('test'));
        $this->assertFalse($list->has('test3'));
        $this->assertTrue($list->has(TestString::fromString('test')));
        $this->assertEquals(['test', 'test2'], $list->values()->toArray());
    }

    public function testNeedKeyThrowsAnException(): void
    {
        $this->expectExceptionMessageMatches('/No key found for item/');
        $list = TestList::fromArray(['test' => 'test', 'test2' => 'test2']);
        $list->needKey('test3');
    }

    public function testListWithDifferentTypes(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid list item type/');
        TestList::fromItems(
            [  // @phpstan-ignore-line
                TestString::fromString('test'),
                TestString::fromString('test2'),
                TestEmail::fromString('arne@arne.be'),
            ],
        );
    }

    public function testEmptyList(): void
    {
        $emptyList = TestList::emptyList();
        $testString = TestString::fromString('test');

        $this->assertEmpty($emptyList->toArray());
        $this->assertTrue($emptyList->isEmpty());
        $this->assertEquals('[]', (string) $emptyList);
        $this->assertEquals('test', $emptyList->first($testString)?->toString());
        $this->assertEquals('test', $emptyList->last($testString)?->toString());
        $this->assertEquals(2, $emptyList->lastKey(2));
        $this->assertEquals(2, $emptyList->firstKey(2));
    }

    public function testListNeedsAnArray(): void
    {
        $this->expectExceptionMessageMatches('/No array given/');
        TestList::fromValue('test');
    }

    public function testListSort(): void
    {
        $unsortedList = [
            'Test A',
            'Test Z',
            'Test B',
        ];
        $sortingFunction = static fn (string $stringA, string $stringB) => $stringA <=> $stringB;
        $sortedList = $unsortedList;
        usort($sortedList, $sortingFunction);

        $unsortedListObject = TestList::fromArray($unsortedList);
        $sortedListObject = $unsortedListObject->usort(
            static fn (TestString $stringA, TestString $stringB) => $sortingFunction(
                $stringA->toString(),
                $stringB->toString(),
            ),
        );

        $this->assertEquals($sortedList, $sortedListObject->toArray());
        $this->assertEquals($unsortedList, $unsortedListObject->toArray());
    }
}
