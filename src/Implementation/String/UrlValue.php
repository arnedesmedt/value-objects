<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\UrlException;

use function filter_var;
use function parse_url;
use function preg_match;
use function str_contains;

use const FILTER_VALIDATE_URL;

abstract class UrlValue extends StringValue
{
    protected function __construct(string $value)
    {
        if (
            ! filter_var($value, FILTER_VALIDATE_URL)
            || str_contains($value, '$IFS') // A hint of abusers (https://bash.cyberciti.biz/guide/$IFS)
            || preg_match('/\$\(.+\)/', $value) // Avoid $() code injection
            || preg_match('/[`"]+/', $value) // Avoid `` code injection, and some extra chars
            || parse_url($value) === false
        ) {
            throw UrlException::noValidUrl($value, static::class);
        }

        parent::__construct($value);
    }
}
