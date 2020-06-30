<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface IsIdentifier
{
    /**
     * @return class-string
     */
    public static function resource(): string;
}
