<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\UrlException;

use function filter_var;
use function parse_url;
use function preg_match;
use function strpos;

use const FILTER_VALIDATE_URL;

abstract class UrlValue extends PatternValue
{
    protected function __construct(string $value)
    {
        if (
            ! filter_var($value, FILTER_VALIDATE_URL)
            || strpos($value, '$IFS') !== false // A hint of abusers (https://bash.cyberciti.biz/guide/$IFS)
            || preg_match('/\$\(.+\)/', $value) // Avoid $() code injection
            || preg_match('/[`"]+/', $value) // Avoid `` code injection, and some extra chars
            || parse_url($value) === false
        ) {
            throw UrlException::noValidUrl($value, static::class);
        }

        parent::__construct($value);
    }

    public static function pattern(): string
    {
        return '^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})' .
            '(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})' .
            '(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}' .
            '(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|' .
            '(?:(?:[a-zA-Z\x{00a1}-\x{ffff}0-9]+-?)*[a-zA-Z\x{00a1}-\x{ffff}0-9]+)' .
            '(?:\.(?:[a-zA-Z\x{00a1}-\x{ffff}0-9]+-?)*[a-zA-Z\x{00a1}-\x{ffff}0-9]+)*' .
            '(?:\.(?:[a-zA-Z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$';
    }
}
