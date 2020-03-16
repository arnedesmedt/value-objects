<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface UuidValue extends StringValue
{
    /**
     * @return static
     */
    public static function generate();
}
