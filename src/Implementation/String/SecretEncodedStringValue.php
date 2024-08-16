<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use ADS\ValueObjects\SecretEncodedStringValue as SecretEncodedStringInterface;

abstract class SecretEncodedStringValue extends StringValue implements SecretEncodedStringInterface
{
}
