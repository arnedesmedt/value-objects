<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation;

use ADS\ValueObjects\FloatValue;
use ADS\ValueObjects\IntValue;
use RuntimeException;

use function is_float;
use function is_int;
use function is_scalar;
use function pow;
use function sprintf;

/**
 * @method int|float toValue()
 * @method __construct(mixed $value)
 */
trait CalcValue
{
    /**
     * @return static
     */
    public function add(FloatValue|IntValue $value): static
    {
        $this->checkInstance($value);

        return new static($this->toValue() + $value->toValue());
    }

    /**
     * @return static
     */
    public function substract(FloatValue|IntValue $value): static
    {
        $this->checkInstance($value);

        return new static($this->toValue() - $value->toValue());
    }

    /**
     * @return static
     */
    public function multiply(FloatValue|IntValue|int|float $value): static
    {
        return new static($this->toValue() * $this->toScalar($value));
    }

    /**
     * @return static
     */
    public function divide(FloatValue|IntValue $value): static
    {
        $toScalar = $this->toScalar($value);

        if ($toScalar === 0) {
            throw new RuntimeException('Can\'t divide by zero.');
        }

        $result = $this->toValue() / $toScalar;

        return new static($this->typeResult($result));
    }

    /**
     * @return static
     */
    public function pow(FloatValue|IntValue $value): static
    {
        $result = pow($this->toScalar($this), $this->toScalar($value));

        return new static($this->typeResult($result));
    }

    /**
     * @return static
     */
    public function square(FloatValue|IntValue $value): static
    {
        $result = pow($this->toScalar($this), 1 / $this->toScalar($value));

        return new static($this->typeResult($result));
    }

    private function checkInstance(FloatValue|IntValue $value): void
    {
        if ($value instanceof static) {
            return;
        }

        throw new RuntimeException(
            sprintf('Given value \'%s\' should be an instance of \'%s\'.', (string) $value, static::class)
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

    private function typeResult(float|int $result): int|float
    {
        if (is_float($result) && $this instanceof IntValue) {
            throw new RuntimeException(
                sprintf('Result of divide can\'t be a float for value object \'%s\'.', static::class)
            );
        }

        if (is_int($result) && $this instanceof FloatValue) {
            $result = (float) $result;
        }

        return $result;
    }
}
