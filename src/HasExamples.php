<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface HasExamples
{
    /**
     * @return static
     */
    public static function example();

    /**
     * @return array<static>
     */
    public static function examples() : array;
}
