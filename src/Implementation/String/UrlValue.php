<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\UriException;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;

use function filter_var;
use function parse_url;

use const FILTER_VALIDATE_URL;

abstract class UrlValue extends UriValue implements ProvidesValidationRules
{
    protected function __construct(string $value)
    {
        if (
            ! filter_var($value, FILTER_VALIDATE_URL)
            || parse_url($value) === false
        ) {
            throw UriException::noValidUrl($value, static::class);
        }

        parent::__construct($value);
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'uri'];
    }
}
