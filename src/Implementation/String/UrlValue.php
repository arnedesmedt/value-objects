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
use function str_replace;

use const FILTER_VALIDATE_URL;

abstract class UrlValue extends UriValue implements ProvidesValidationRules
{
    protected function __construct(string $value)
    {
        /** @var array{scheme: string, host: string, path: string}|false $parsedUrl */
        $parsedUrl = parse_url($value);

        if ($parsedUrl === false) {
            throw UriException::noValidUrl($value, static::class);
        }

        $host = idn_to_ascii($parsedUrl['host']);

        if ($host === false) {
            throw UriException::noAsciiFormat($value, static::class);
        }

        $url = str_replace($parsedUrl['host'], $host, $value);

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw UriException::noValidUrl($value, static::class);
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
