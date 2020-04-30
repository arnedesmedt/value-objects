<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use RuntimeException;
use function base64_decode;
use function base64_encode;
use function sprintf;

abstract class Base64EncodedStringValue extends StringValue
{
    /**
     * @return static
     */
    public static function fromPlainString(string $plainString)
    {
        return static::fromString(base64_encode($plainString));
    }

    public function toPlainString() : string
    {
        /** @var string|false $decoded */
        $decoded = base64_decode($this->toString());

        if ($decoded === false) {
            throw new RuntimeException(
                sprintf(
                    'Could not base64 decode string %s',
                    $this->toString()
                )
            );
        }

        return $decoded;
    }
}
