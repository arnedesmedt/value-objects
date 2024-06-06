<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception\EncryptDecrypt;

use function sprintf;

final class NoSecretKeyFoundException extends InvalidSecretKeyException
{
    public function __construct(string $environmentKey)
    {
        parent::__construct(
            sprintf(
                'No secret key found, please ensure that a value is set in $_ENV[\'%s\'].',
                $environmentKey,
            ),
        );
    }
}
