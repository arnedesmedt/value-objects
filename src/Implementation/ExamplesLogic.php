<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation;

trait ExamplesLogic
{
    /** @return array<static> */
    public static function examples(): array
    {
        /** @var static|null $example */
        $example = static::example();

        if ($example === null) {
            return [];
        }

        return [$example];
    }
}
