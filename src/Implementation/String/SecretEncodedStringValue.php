<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Service\EncryptDecryptService;

abstract class SecretEncodedStringValue extends StringValue
{
    final protected function __construct(string $value, protected string $plainValue)
    {
        parent::__construct($value);
    }

    public static function fromString(string $value): static
    {
        return new static(EncryptDecryptService::encrypt($value), EncryptDecryptService::decrypt($value));
    }

    public function toPlainString(): string
    {
        return $this->plainValue;
    }

    public static function example(): static
    {
        return static::fromString('This is a secret message');
    }
}
