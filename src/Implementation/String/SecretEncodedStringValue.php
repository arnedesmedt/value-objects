<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Service\EncryptDecryptService;

abstract class SecretEncodedStringValue extends StringValue
{
    public static function fromPlainString(string $plainString): static
    {
        return static::fromString(EncryptDecryptService::encrypt($plainString));
    }

    public function toPlainString(): string
    {
        return EncryptDecryptService::decrypt($this->toString());
    }

    public static function example(): static
    {
        return static::fromPlainString('This is a secret message');
    }
}
