<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Service;

use ADS\ValueObjects\Exception\EncryptDecrypt\DecryptionFailedException;
use ADS\ValueObjects\Exception\EncryptDecrypt\NoSecretKeyFoundException;
use ADS\ValueObjects\Exception\EncryptDecrypt\SecretKeyNotRequiredLengthException;
use Throwable;

use function base64_decode;
use function base64_encode;
use function hex2bin;
use function is_string;
use function ltrim;
use function mb_strlen;
use function mb_substr;
use function random_bytes;
use function rtrim;
use function sodium_crypto_secretbox;
use function sodium_crypto_secretbox_open;
use function sodium_memzero;
use function str_ends_with;
use function str_starts_with;

use const SODIUM_CRYPTO_SECRETBOX_KEYBYTES;
use const SODIUM_CRYPTO_SECRETBOX_MACBYTES;
use const SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

/** @see https://stackoverflow.com/questions/34477643/how-to-encrypt-decrypt-aes-with-libsodium-php */
final class EncryptDecryptService
{
    public const ENVIRONMENT_SECRET_KEY_KEY = 'ADS_SECRET_KEY';

    public const ENCRYPTED_PREFIX = '<ADS_ENC>';
    public const ENCRYPTED_SUFFIX = '</ADS_ENC>';

    public const ENVIRONMENT_SECRET_KEY_REQUIRED_BYTES_LENGTH = SODIUM_CRYPTO_SECRETBOX_KEYBYTES;

    public static function encrypt(string $message): string
    {
        if (self::isSupportedEncryptedString($message)) {
            return $message;
        }

        $key = self::secretKey();

        $nonce = random_bytes(
            SODIUM_CRYPTO_SECRETBOX_NONCEBYTES,
        );

        $cipher = base64_encode(
            $nonce .
            sodium_crypto_secretbox(
                $message,
                $nonce,
                $key,
            ),
        );
        sodium_memzero($message);
        sodium_memzero($key);

        return self::ENCRYPTED_PREFIX . $cipher . self::ENCRYPTED_SUFFIX;
    }

    public static function decrypt(string $encrypted): string
    {
        if (! self::isSupportedEncryptedString($encrypted)) {
            return $encrypted;
        }

        $encrypted = rtrim(ltrim($encrypted, self::ENCRYPTED_PREFIX), self::ENCRYPTED_SUFFIX);

        $key = self::secretKey();
        /** @var string|false $decoded */
        $decoded = base64_decode($encrypted, true);
        if ($decoded === false) {
            throw new DecryptionFailedException();
        }

        if (mb_strlen($decoded, '8bit') < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
            throw new DecryptionFailedException();
        }

        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plain = sodium_crypto_secretbox_open(
            $ciphertext,
            $nonce,
            $key,
        );
        if ($plain === false) {
            throw new DecryptionFailedException();
        }

        sodium_memzero($ciphertext);
        sodium_memzero($key);

        return $plain;
    }

    private static function secretKey(): string
    {
        $key = $_ENV[self::ENVIRONMENT_SECRET_KEY_KEY] ?? null;

        if (! is_string($key)) {
            throw new NoSecretKeyFoundException(self::ENVIRONMENT_SECRET_KEY_KEY);
        }

        try {
            $binarySecretKey = hex2bin($key);
        } catch (Throwable) {
            $binarySecretKey = false;
        }

        if ($binarySecretKey === false) {
            throw new NoSecretKeyFoundException(self::ENVIRONMENT_SECRET_KEY_KEY);
        }

        $keyLength = mb_strlen($binarySecretKey, '8bit');
        if ($keyLength !== self::ENVIRONMENT_SECRET_KEY_REQUIRED_BYTES_LENGTH) {
            throw new SecretKeyNotRequiredLengthException($keyLength);
        }

        return $binarySecretKey;
    }

    private static function isSupportedEncryptedString(string $message): bool
    {
        return str_starts_with($message, self::ENCRYPTED_PREFIX) && str_ends_with($message, self::ENCRYPTED_SUFFIX);
    }
}
