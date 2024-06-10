<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Implementation\String\SecretEncodedStringValue;
use ADS\ValueObjects\Tests\Object\ValueObject\String\TestSecretEncoded;
use PHPUnit\Framework\TestCase;

class EncryptedStringTest extends TestCase
{
    public function testSecretEncoded(): void
    {
        EncryptDecryptTest::setCorrectSecretKey();
        $plainString = 'My secret value.';
        $secretEncodedString = TestSecretEncoded::fromString($plainString);

        $this->assertNotEquals($plainString, $secretEncodedString->toString());
        $this->assertEquals($plainString, $secretEncodedString->toPlainString());
    }

    public function testAlreadySecretEncodedDoesntGetEncodedTwice(): void
    {
        EncryptDecryptTest::setCorrectSecretKey();
        $plainString = 'My secret value.';
        $secretEncodedString = TestSecretEncoded::fromString($plainString);
        $encryptedString = $secretEncodedString->toString();

        $secondSecretEncodedString = TestSecretEncoded::fromString($encryptedString);

        $this->assertNotEquals($plainString, $secretEncodedString->toString());
        $this->assertEquals($secretEncodedString->toString(), $secondSecretEncodedString->toString());
        $this->assertEquals($plainString, $secondSecretEncodedString->toPlainString());
    }

    public function testExampleSecretEncoded(): void
    {
        EncryptDecryptTest::setCorrectSecretKey();
        $this->assertInstanceOf(SecretEncodedStringValue::class, TestSecretEncoded::example());
    }
}
