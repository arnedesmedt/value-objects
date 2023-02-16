<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\PatternException;
use ADS\ValueObjects\PatternValue as PatternValueInterface;
use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;

use function preg_match;
use function sprintf;

abstract class PatternValue extends StringValue implements PatternValueInterface, ProvidesValidationRules
{
    public const BELL = "\x07";

    protected function __construct(string $value)
    {
        $pattern = static::pregMatchPattern();

        if (! preg_match($pattern, $value)) {
            throw PatternException::noMatch($value, $pattern, static::class);
        }

        parent::__construct($value);
    }

    abstract public static function pattern(): string;

    public static function pregMatchPattern(): string
    {
        return sprintf(self::BELL . '%s' . self::BELL . 'uD', static::pattern());
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::PATTERN => static::pattern()];
    }
}
