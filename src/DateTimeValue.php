<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects;

use DateTimeInterface;
use EventEngine\JsonSchema\ProvidesValidationRules;

interface DateTimeValue extends StringValue, ProvidesValidationRules
{
    public static function fromDateTime(DateTimeInterface $value): static;

    public function toDateTime(): DateTimeInterface;

    public function toFormattedString(string $format): string;

    public static function now(): static;
}
