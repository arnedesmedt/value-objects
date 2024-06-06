<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception\EncryptDecrypt;

use ADS\ValueObjects\Exception\ValueObjectException;
use ADS\ValueObjects\Service\EncryptDecryptService;

use function sprintf;

use const PHP_EOL;

abstract class InvalidSecretKeyException extends ValueObjectException
{
    public function __construct(string $message)
    {
        parent::__construct(
            sprintf(
                '%s' . PHP_EOL .
                'You can use the following script to generate a key with the correct length:' . PHP_EOL .
                'php -r "echo bin2hex(random_bytes(%d));"',
                $message,
                EncryptDecryptService::ENVIRONMENT_SECRET_KEY_REQUIRED_BYTES_LENGTH,
            ),
        );
    }
}
