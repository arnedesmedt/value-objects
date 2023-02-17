<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use EventEngine\Data\ImmutableRecord;

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

        return $data;
    }
}
