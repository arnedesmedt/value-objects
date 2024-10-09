<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

use TeamBlue\ValueObjects\SensitiveValue as SensitiveInterface;

abstract class SensitiveValue extends StringValue implements SensitiveInterface
{
}
