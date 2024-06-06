<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Exception\EncryptDecrypt;

use ADS\ValueObjects\Exception\ValueObjectException;

final class DecryptionFailedException extends ValueObjectException
{
    public function __construct()
    {
        parent::__construct('Decryption failed.');
    }
}
