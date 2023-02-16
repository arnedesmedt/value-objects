<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Implementation\String\DateTimeValue;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestBase64Encoded;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestByte;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestEmail;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestHostname;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestIpv4;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestIpv6;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestPattern;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestString;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestUrl;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestUuid;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
    public function testStringValue(): void
    {
        $string = TestString::fromString('test');

        $this->assertEquals('test', $string->toString());
        $this->assertEquals('test', $string->toValue());
        $this->assertEquals('test', $string);
    }

    public function testExampleString(): void
    {
        $this->assertInstanceOf(TestString::class, TestString::example());
    }

    public function testStringEquals(): void
    {
        $string = TestString::fromString('test');
        $compareString = TestString::fromValue('test');
        $notCompareString = TestString::fromString('not test');

        $this->assertTrue($string->isEqualTo($compareString));
        $this->assertFalse($string->isEqualTo($notCompareString));
        $this->assertFalse($string->isEqualTo(TestUuid::generate()));
    }

    public function testPatternValid(): void
    {
        $pattern = TestPattern::fromString('aR-ne');
        $this->assertEquals('aR-ne', $pattern->toString());
    }

    public function testPatternInvalid(): void
    {
        $this->expectExceptionMessageMatches('/The value \'.+\' does not match pattern/');
        TestPattern::fromString('-test');
    }

    public function testUrlValue(): void
    {
        $url = TestUrl::fromString('https://www.google.com/');
        $urlWithoutSlash = TestUrl::fromString('https://www.google.com');
        $this->assertEquals('https://www.google.com/', $url->toString());
        $this->assertEquals('https://www.google.com', $url->toStringWithoutTrailingSlash());
        $this->assertEquals('https://www.google.com/', $urlWithoutSlash->toStringWithTrailingSlash());
    }

    public function testExampleUrl(): void
    {
        $this->assertInstanceOf(TestUrl::class, TestUrl::example());
    }

    public function testRulesUrl(): void
    {
        $this->assertEquals(
            ['format' => 'uri'],
            TestUrl::validationRules(),
        );
    }

    public function testInvalidUrlValue(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid url for value object/');
        TestUrl::fromString('https://www.google.com?$IFS');
    }

    public function testIpv4Value(): void
    {
        $ipv4 = TestIpv4::fromString('1.2.3.4');
        $this->assertEquals('1.2.3.4', $ipv4);
    }

    public function testExampleIpv4(): void
    {
        $this->assertInstanceOf(TestIpv4::class, TestIpv4::example());
    }

    public function testRulesIpv4(): void
    {
        $this->assertEquals(
            [
                'format' => 'ipv4',
                'pattern' => '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$', // phpcs:ignore Generic.Files.LineLength.TooLong
            ],
            TestIpv4::validationRules(),
        );
    }

    public function testInvalidIpv4Value(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid ipv4 for value object/');
        TestIpv4::fromString('1.2.3.4.5');
    }

    public function testIpv6Value(): void
    {
        $ipv6 = TestIpv6::fromString('2001:db8:3333:4444:5555:6666:7777:8888');
        $this->assertEquals('2001:db8:3333:4444:5555:6666:7777:8888', $ipv6);
    }

    public function testExampleIpv6(): void
    {
        $this->assertInstanceOf(TestIpv6::class, TestIpv6::example());
    }

    public function testRulesIpv6(): void
    {
        $this->assertEquals(
            [
                'format' => 'ipv6',
                'pattern' => '^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$', // phpcs:ignore Generic.Files.LineLength.TooLong
            ],
            TestIpv6::validationRules(),
        );
    }

    public function testInvalidIpv6Value(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid ipv6 for value object/');
        TestIpv6::fromString('2001:db8:3333:4444:5555:6666:7777:8888:56');
    }

    public function testUuid(): void
    {
        $uuid = TestUuid::generate();
        $this->assertEquals($uuid->toValue(), $uuid);
    }

    public function testExampleUuid(): void
    {
        $this->assertInstanceOf(TestUuid::class, TestUuid::example());
    }

    public function testRulesUuid(): void
    {
        $this->assertEquals(
            [
                'format' => 'uuid',
                'pattern' => '^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$',
            ],
            TestUuid::validationRules(),
        );
    }

    public function testUuidEquals(): void
    {
        $uuid = TestUuid::generate();
        $compareUuid = TestUuid::fromString($uuid->toString());
        $notCompareUuid = TestUuid::fromValue(TestUuid::generate()->toValue());

        $this->assertTrue($uuid->isEqualTo($compareUuid));
        $this->assertFalse($uuid->isEqualTo($notCompareUuid));
        $this->assertFalse($uuid->isEqualTo(TestString::fromString('test')));
    }

    public function testEmail(): void
    {
        $email = TestEmail::fromString('arne@email.be');
        $this->assertEquals('arne@email.be', $email);
    }

    public function testExampleEmail(): void
    {
        $this->assertInstanceOf(TestEmail::class, TestEmail::example());
    }

    public function testRulesEmail(): void
    {
        $this->assertEquals(['format' => 'email'], TestEmail::validationRules());
    }

    public function testNonValidEmail(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid e-mail for value object/');
        TestEmail::fromString('arne');
    }

    public function testNonValidAsciiEmail(): void
    {
        $this->expectExceptionMessageMatches('/to IDNA ASCII form for value object/');
        TestEmail::fromString('');
    }

    public function testDateTime(): void
    {
        $dateTime = DateTimeValue::fromValue('2021-01-01 00:00:00');

        $this->assertEquals('2021-01-01T00:00:00.000+00:00', $dateTime->toString());
        $this->assertEquals('2021-01-01T00:00:00.000+00:00', $dateTime);
        $this->assertEquals('2021-01-01T00:00:00.000+00:00', $dateTime->toValue());
        $this->assertEquals('2021-01-01T00:00:00+00:00', $dateTime->toDateTime()->format('c'));
    }

    public function testExampleDateTime(): void
    {
        $this->assertInstanceOf(DateTimeValue::class, DateTimeValue::example());
    }

    public function testRulesDateTime(): void
    {
        $this->assertEquals(['format' => 'date-time'], DateTimeValue::validationRules());
    }

    public function testDateTimeEquals(): void
    {
        $dateTime = DateTimeValue::fromValue('2021-01-01 00:00:00');
        $now = DateTimeValue::now();
        $dateTime2 = DateTimeValue::fromDateTime($dateTime->toDateTime());

        $this->assertNotEmpty($now->toValue());
        $this->assertTrue($dateTime->isEqualTo($dateTime2));
        $this->assertFalse($dateTime->isEqualTo($now));
        $this->assertFalse($dateTime->isEqualTo(TestString::fromString('test')));
    }

    public function testInvalidDateTime(): void
    {
        $this->expectExceptionMessageMatches('/is not a valid date time for value object/');
        DateTimeValue::fromString('arne');
    }

    public function testBase64Encoded(): void
    {
        $base64Encoded = TestBase64Encoded::fromPlainString('test');

        $this->assertEquals('dGVzdA==', $base64Encoded->toString());
        $this->assertEquals('test', $base64Encoded->toPlainString());
    }

    public function testExampleBase64Encoded(): void
    {
        $this->assertInstanceOf(TestBase64Encoded::class, TestBase64Encoded::example());
    }

    /** @return array<string, array<string, mixed>> */
    public static function byteDataProvider(): array
    {
        return [
            'test-bytes' => [
                'input' => '156B',
                'expected' => '0.00014877319335938Mb',
            ],
            'test-kilo-bytes' => [
                'input' => '1kb',
                'expected' => '0.0009765625Mb',
            ],
            'test-mega-bytes' => [
                'input' => '1Mb',
                'expected' => '1Mb',
            ],
            'test-mega-bytes-empty' => [
                'input' => '1',
                'expected' => '1Mb',
            ],
            'test-bytes-with-method' => [
                'input' => '156B',
                'expected' => '156b',
                'inputMethod' => 'fromB',
                'outputMethod' => 'toB',
            ],
        ];
    }

    /** @dataProvider byteDataProvider */
    public function testByteMethods(
        mixed $input,
        string $expected,
        string $inputMethod = 'fromString',
        string $outputMethod = 'toString'
    ): void {
        $byteValue = TestByte::$inputMethod($input);

        $this->assertEquals($expected, $byteValue->$outputMethod());
    }

    public function testInvalidBytePattern(): void
    {
        $this->expectExceptionMessageMatches('/Invalid byte pattern given/');
        TestByte::fromString('arne');
    }

    public function testInvalidFromMethodForByte(): void
    {
        $this->expectExceptionMessageMatches('/No method \'fromTest\' found for class/');
        TestByte::fromTest('arne'); // @phpstan-ignore-line
    }

    public function testInvalidToMethodForByte(): void
    {
        $this->expectExceptionMessageMatches('/No method \'toLalal\' found for class/');
        $test = TestByte::fromString('156');
        $test->toLalal(); // @phpstan-ignore-line
    }

    public function testExampleHostname(): void
    {
        $this->assertInstanceOf(TestHostname::class, TestHostname::example());
    }

    public function testRulesHostname(): void
    {
        $this->assertEquals(
            ['format' => 'hostname'],
            TestHostname::validationRules(),
        );
    }
}
