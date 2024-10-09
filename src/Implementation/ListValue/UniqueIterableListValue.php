<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\ListValue;

/**
 * @template T of object
 * @template-extends IterableListValue<T>
 */
abstract class UniqueIterableListValue extends IterableListValue
{
    public static function uniqueItems(): bool|null
    {
        return true;
    }
}
