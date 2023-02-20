<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use EventEngine\Data\ImmutableRecord;

use function array_map;
use function is_array;

class Util
{
    public static function toScalar(mixed $data): mixed
    {
        if ($data instanceof ValueObject) {
            return $data->toValue();
        }

        if ($data instanceof ImmutableRecord) {
            return $data->toArray();
        }

        if (is_array($data)) {
            return array_map(
                static fn ($item) => self::toScalar($item),
                $data,
            );
        }

        return $data;
    }
}
