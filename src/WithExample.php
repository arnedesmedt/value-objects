<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface WithExample
{
    /**
     * @return static
     */
    public static function example();
}
