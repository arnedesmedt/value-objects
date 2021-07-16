<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

use DateTimeInterface;
use EventEngine\JsonSchema\ProvidesValidationRules;

interface DateTimeValue extends StringValue, ProvidesValidationRules
{
    /**
     * @return static
     */
    public static function fromDateTime(DateTimeInterface $value);

    public function toDateTime(): DateTimeInterface;

    public function toFormattedString(string $format): string;

    /**
     * @return static
     */
    public static function now();
}
