<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Util;

use Throwable;

use function reset;

/**
 * @template T
 */
trait CollectionUtil
{
    /**
     * @param array<T> $items
     *
     * @return T
     */
    protected function firstItemOfCollection(array $items, Throwable $exception)
    {
        $item = reset($items);

        if ($item === false) {
            throw $exception;
        }

        return $item;
    }
}
