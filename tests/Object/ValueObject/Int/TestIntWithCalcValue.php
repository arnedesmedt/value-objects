<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Tests\Object\ValueObject\Int;

use TeamBlue\ValueObjects\Implementation\CalcValue;
use TeamBlue\ValueObjects\Implementation\Int\IntValue;

class TestIntWithCalcValue extends IntValue
{
    use CalcValue;
}
