<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use DateTimeInterface;

interface DateTimeValue extends StringValue
{
    public static function fromDateTime(DateTimeInterface $value): DateTimeValue;

    public function toDateTime(): DateTimeInterface;

    public function toFormattedString(string $format): string;
}
