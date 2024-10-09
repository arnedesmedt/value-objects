<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use TeamBlue\ValueObjects\Exception\UriException;

use function preg_match;
use function str_contains;

abstract class UriValue extends StringValue
{
    protected function __construct(string $value)
    {
        if (
            str_contains($value, '$IFS') // A hint of abusers (https://bash.cyberciti.biz/guide/$IFS)
            || preg_match('/\$\(.+\)/', $value) // Avoid $() code injection
            || preg_match('/[`"]+/', $value) // Avoid `` code injection, and some extra chars
        ) {
            throw UriException::noValidUri($value, static::class);
        }

        parent::__construct($value);
    }

    public static function example(): static
    {
        return static::fromString('http://test.be');
    }
}
