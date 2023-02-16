<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface HasExamples
{
    /**
     * @return static|null
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public static function example();

    /**
     * @return array<static>
     */
    public static function examples(): array;
}
