<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\UriException;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;

use function filter_var;
use function idn_to_ascii;
use function parse_url;
use function rtrim;

use const FILTER_VALIDATE_URL;

abstract class UrlValue extends UriValue implements ProvidesValidationRules
{
    protected function __construct(string $value)
    {
        $url = idn_to_ascii($value);

        if ($url === false) {
            throw UriException::noAsciiFormat($value, static::class);
        }

        if (
            ! filter_var($url, FILTER_VALIDATE_URL)
            || parse_url($url) === false
        ) {
            throw UriException::noValidUrl($url, static::class);
        }

        parent::__construct($url);
    }

    public function toStringWithTrailingSlash(): string
    {
        return $this->toStringWithoutTrailingSlash() . '/';
    }

    public function toStringWithoutTrailingSlash(): string
    {
        return rtrim(parent::toString(), '/');
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'uri'];
    }
}
