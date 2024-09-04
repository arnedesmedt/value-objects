<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\SensitiveValue as SensitiveInterface;

abstract class SensitiveValue extends StringValue implements SensitiveInterface
{
}
