<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface HasExamples
{
    public static function example(): static;

    /**
     * @return array<static>
     */
    public static function examples(): array;
}
