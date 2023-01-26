<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Implementation\String\DateTimeValue;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestBase64Encoded;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestEmail;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestIpv4;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestIpv6;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestPattern;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestString;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestUrl;
use ADS\ValueObjects\Tests\Unit\ValueObject\String\TestUuid;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
    public function testString(): void
    {
        $string = TestString::fromString('test');
        $compareString = TestString::fromValue('test');
        $notCompareString = TestString::fromString('not test');

        $this->assertEquals('test', $string->toString());
        $this->assertEquals('test', $compareString->toValue());
        $this->assertTrue($string->isEqualTo($compareString));
        $this->assertFalse($string->isEqualTo($notCompareString));
        $this->assertFalse($string->isEqualTo(TestUuid::generate()));
        $this->assertEquals('test', $string);
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

    public function testIpv6Value(): void
    {
        $ipv6 = TestIpv6::fromString('2001:db8:3333:4444:5555:6666:7777:8888');
        $this->assertEquals('2001:db8:3333:4444:5555:6666:7777:8888', $ipv6);
    }

    public function testUuid(): void
    {
        $uuid = TestUuid::generate();
        $compareUuid = TestUuid::fromString($uuid->toString());
        $notCompareUuid = TestUuid::fromValue(TestUuid::generate()->toValue());
        $this->assertTrue($uuid->isEqualTo($compareUuid));
        $this->assertEquals($uuid->toValue(), $uuid);
        $this->assertFalse($uuid->isEqualTo($notCompareUuid));
        $this->assertFalse($uuid->isEqualTo(TestString::fromString('test')));
    }

    public function testEmail(): void
    {
        $email = TestEmail::fromString('arne@email.be');
        $this->assertEquals('arne@email.be', $email);
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
        $now = DateTimeValue::now();
        $dateTime2 = DateTimeValue::fromDateTime($dateTime->toDateTime());

        $this->assertEquals('2021-01-01T00:00:00.000+00:00', $dateTime->toString());
        $this->assertEquals('2021-01-01T00:00:00.000+00:00', $dateTime);
        $this->assertEquals('2021-01-01T00:00:00.000+00:00', $dateTime->toValue());
        $this->assertEquals('2021-01-01T00:00:00+00:00', $dateTime->toDateTime()->format('c'));
        $this->assertTrue($dateTime->isEqualTo($dateTime2));
        $this->assertFalse($dateTime->isEqualTo($now));
        $this->assertFalse($dateTime->isEqualTo(TestString::fromString('test')));
        $this->assertNotEmpty($now->toValue());
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
}
