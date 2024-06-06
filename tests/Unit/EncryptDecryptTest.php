<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit;

use ADS\ValueObjects\Exception\EncryptDecrypt\DecryptionFailedException;
use ADS\ValueObjects\Exception\EncryptDecrypt\NoSecretKeyFoundException;
use ADS\ValueObjects\Exception\EncryptDecrypt\SecretKeyNotRequiredLengthException;
use ADS\ValueObjects\Service\EncryptDecryptService;
use PHPUnit\Framework\TestCase;

use function base64_encode;
use function bin2hex;
use function random_bytes;

class EncryptDecryptTest extends TestCase
{
    public function testNoSecretKeySet(): void
    {
        $this->expectException(NoSecretKeyFoundException::class);
        EncryptDecryptService::encrypt('this should fail');
    }

    public function testSecretKeyIsNotAString(): void
    {
        $_ENV[EncryptDecryptService::ENVIRONMENT_SECRET_KEY_KEY] = 123;
        $this->expectException(NoSecretKeyFoundException::class);
        EncryptDecryptService::encrypt('this should fail');
    }

    public function testToShortSecretKeySet(): void
    {
        $lengthOfKey = EncryptDecryptService::ENVIRONMENT_SECRET_KEY_REQUIRED_BYTES_LENGTH - 1;
        $_ENV[EncryptDecryptService::ENVIRONMENT_SECRET_KEY_KEY] = bin2hex(random_bytes($lengthOfKey));
        $this->expectException(SecretKeyNotRequiredLengthException::class);
        EncryptDecryptService::encrypt('this should fail');
    }

    public function testDecryptInvalidKey(): void
    {
        $_ENV[EncryptDecryptService::ENVIRONMENT_SECRET_KEY_KEY] = 'thisisaninvalidkey';
        $this->expectException(NoSecretKeyFoundException::class);
        EncryptDecryptService::decrypt('This will fail, but the key should be the error');
    }

    public function testEncryptString(): void
    {
        self::setCorrectSecretKey();
        $secretMessage = 'this is a secret message';
        $encrypted = EncryptDecryptService::encrypt($secretMessage);
        $this->assertIsString($encrypted);
        $this->assertNotEquals($secretMessage, $encrypted);
    }

    public function testDecryptFailsBase64Decode(): void
    {
        self::setCorrectSecretKey();
        $this->expectException(DecryptionFailedException::class);
        EncryptDecryptService::decrypt('this should fail===');
    }

    public function testDecryptFailsInvalidEncryptedValue(): void
    {
        self::setCorrectSecretKey();
        $this->expectException(DecryptionFailedException::class);
        EncryptDecryptService::decrypt(base64_encode('this should fail'));
    }

    public function testDecryptFailsButMightHaveNonceAndCipherText(): void
    {
        self::setCorrectSecretKey();
        $this->expectException(DecryptionFailedException::class);
        EncryptDecryptService::decrypt(base64_encode(bin2hex(random_bytes(60))));
    }

    public function testDecryptionIsSuccessful(): void
    {
        self::setCorrectSecretKey();
        $message = 'this is a secret message';
        $encrypted = EncryptDecryptService::encrypt($message);
        $decrypted = EncryptDecryptService::decrypt($encrypted);
        $this->assertEquals($message, $decrypted);
    }

    public static function setCorrectSecretKey(): void
    {
        $_ENV[EncryptDecryptService::ENVIRONMENT_SECRET_KEY_KEY] = bin2hex(
            random_bytes(
                EncryptDecryptService::ENVIRONMENT_SECRET_KEY_REQUIRED_BYTES_LENGTH,
            ),
        );
    }
}
