<?php

declare(strict_types=1);

namespace ADS\ValueObjects;

interface CalcValueObject extends ValueObject
{
    public function add(FloatValue|IntValue $value): static;

    public function substract(FloatValue|IntValue $value): static;

    public function multiply(FloatValue|IntValue $value): static;

    public function divide(FloatValue|IntValue $value): static;

    public function pow(FloatValue|IntValue $value): static;

    public function square(FloatValue|IntValue $value): static;
}
