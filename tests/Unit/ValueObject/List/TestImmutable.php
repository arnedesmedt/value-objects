<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Tests\Unit\ValueObject\List;

use EventEngine\Data\ImmutableRecord;
use EventEngine\Data\ImmutableRecordLogic;

class TestImmutable implements ImmutableRecord
{
    use ImmutableRecordLogic;

    private string $test;
}
