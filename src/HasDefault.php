<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface HasDefault
{
    /**
     * @return static
     */
    public static function defaultValue(): static;
}
