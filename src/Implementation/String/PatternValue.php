<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\PatternException;
use ADS\ValueObjects\PatternValue as PatternValueInterface;
use Opis\JsonSchema\Validator;

use function preg_match;
use function sprintf;

abstract class PatternValue extends StringValue implements PatternValueInterface
{
    protected function __construct(string $value)
    {
        $pattern = sprintf('%s%s%su', Validator::BELL, static::pattern(), Validator::BELL);

        if (
            ! preg_match(
                $pattern,
                $value,
            )
        ) {
            throw PatternException::noMatch($value, $pattern, static::class);
        }

        parent::__construct($value);
    }

    abstract public static function pattern(): string;
}
