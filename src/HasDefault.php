<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface HasDefault
{
    public static function defaultValue(): static;
}
