<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\Exception\InvalidEmailException;
use function filter_var;
use function idn_to_ascii;
use const FILTER_VALIDATE_EMAIL;
use const IDNA_DEFAULT;
use const INTL_IDNA_VARIANT_UTS46;

abstract class EmailValue extends StringValue
{
    protected function __construct(string $value)
    {
        $email = idn_to_ascii($value, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);

        if ($email === false) {
            throw InvalidEmailException::noAsciiFormat($value, static::class);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::noValidEmail($value, static::class);
        }

        parent::__construct($email);
    }
}
