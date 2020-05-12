<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\UrlException;
use function filter_var;
use const FILTER_VALIDATE_URL;

abstract class UrlValue extends StringValue
{
    protected function __construct(string $value)
    {
        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            throw UrlException::noValidUrl($value, static::class);
        }

        parent::__construct($value);
    }
}
