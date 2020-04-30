<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface WithRules
{
    /**
     * @return array<string, mixed>
     */
    public static function rules() : array;
}
