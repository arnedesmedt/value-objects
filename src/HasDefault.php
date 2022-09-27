<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface HasDefault
{
    /**
     * @return static
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public static function defaultValue();
}
