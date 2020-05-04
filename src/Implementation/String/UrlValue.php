<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\InvalidUrlException;
use function filter_var;
use function idn_to_ascii;
use const FILTER_VALIDATE_URL;
use const IDNA_DEFAULT;
use const INTL_IDNA_VARIANT_UTS46;

abstract class UrlValue extends StringValue
{
    protected function __construct(string $value)
    {
        $url = idn_to_ascii($value, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);

        if ($url === false) {
            throw InvalidUrlException::noAsciiFormat($value, static::class);
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw InvalidUrlException::noValidUrl($value, static::class);
        }

        parent::__construct($url);
    }
}
