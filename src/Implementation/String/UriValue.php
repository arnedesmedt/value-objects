<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\UriException;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use Faker\Factory;

use function preg_match;
use function rtrim;
use function str_contains;

abstract class UriValue extends StringValue implements ProvidesValidationRules
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

    public function toStringWithTrailingSlash(): string
    {
        return $this->toStringWithoutTrailingSlash() . '/';
    }

    public function toStringWithoutTrailingSlash(): string
    {
        return rtrim(parent::toString(), '/');
    }

    public static function example(): static
    {
        $generator = Factory::create();

        return static::fromString($generator->url());
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'uri'];
    }
}
