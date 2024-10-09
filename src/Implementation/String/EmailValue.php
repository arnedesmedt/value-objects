<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use EventEngine\JsonSchema\ProvidesValidationRules;
use EventEngine\JsonSchema\Type\StringType;
use TeamBlue\ValueObjects\Exception\EmailException;

use function filter_var;
use function idn_to_ascii;

use const FILTER_VALIDATE_EMAIL;
use const IDNA_DEFAULT;
use const INTL_IDNA_VARIANT_UTS46;

abstract class EmailValue extends StringValue implements ProvidesValidationRules
{
    protected function __construct(string $value)
    {
        $email = idn_to_ascii($value, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);

        if ($email === false) {
            throw EmailException::noAsciiFormat($value, static::class);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw EmailException::noValidEmail($value, static::class);
        }

        parent::__construct($email);
    }

    public static function example(): static
    {
        return static::fromString('test@test.be');
    }

    /** @return array<string, string> */
    public static function validationRules(): array
    {
        return [StringType::FORMAT => 'email'];
    }
}
