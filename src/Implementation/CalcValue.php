<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation;

use RuntimeException;
use TeamBlue\ValueObjects\FloatValue;
use TeamBlue\ValueObjects\IntValue;

use function is_float;
use function is_int;
use function is_scalar;
use function sprintf;

/** @phpstan-consistent-constructor */
trait CalcValue
{
    public function add(FloatValue|IntValue|int|float $value): static
    {
        $this->checkInstance($value);

        return new static($this->changeType($this->toValue() + $this->toScalar($value)));
    }

    public function substract(FloatValue|IntValue|int|float $value): static
    {
        $this->checkInstance($value);

        return new static($this->changeType($this->toValue() - $this->toScalar($value)));
    }

    public function multiply(FloatValue|IntValue|int|float $value): static
    {
        return new static($this->changeType($this->toValue() * $this->toScalar($value)));
    }

    public function divide(FloatValue|IntValue|int|float $value): static
    {
        $toScalar = $this->toScalar($value);

        if ($toScalar === 0) {
            throw new RuntimeException('Can\'t divide by zero.');
        }

        $result = $this->toValue() / $toScalar;

        return new static($this->changeType($result));
    }

    public function pow(FloatValue|IntValue $value): static
    {
        $result = $this->toScalar($this) ** $this->toScalar($value);

        return new static($this->changeType($result));
    }

    public function square(FloatValue|IntValue $value): static
    {
        $result = $this->toScalar($this) ** (1 / $this->toScalar($value));

        return new static($this->changeType($result));
    }

    public function isLowerThan(FloatValue|IntValue $value): bool
    {
        return $this->toValue() < $value->toValue();
    }

    public function isGreaterThan(FloatValue|IntValue $value): bool
    {
        return $this->toValue() > $value->toValue();
    }

    public function isLowerOrEqualThan(FloatValue|IntValue $value): bool
    {
        return $this->toValue() <= $value->toValue();
    }

    public function isGreaterOrEqualThan(FloatValue|IntValue $value): bool
    {
        return $this->toValue() >= $value->toValue();
    }

    private function checkInstance(FloatValue|IntValue|int|float &$value): void
    {
        if (is_scalar($value)) {
            $value = static::fromValue($this->changeType($value));
        }

        if ($value instanceof static) {
            return;
        }

        throw new RuntimeException(
            sprintf('Given value \'%s\' should be an instance of \'%s\'.', (string) $value, static::class),
        );
    }

    private function toScalar(FloatValue|IntValue|float|int $value): int|float
    {
        return is_scalar($value)
            ? $value
            : (
                $value instanceof FloatValue
                    ? $value->toFloat()
                    : $value->toInt()
            );
    }

    private function changeType(float|int $result): int|float
    {
        if (is_float($result) && $this instanceof IntValue) {
            if ((float) (int) $result !== $result) {
                throw new RuntimeException(
                    sprintf('Result can\'t be a float for value object \'%s\'.', static::class),
                );
            }

            $result = (int) $result;
        }

        if (is_int($result) && $this instanceof FloatValue) {
            $result = (float) $result;
        }

        return $result;
    }
}
