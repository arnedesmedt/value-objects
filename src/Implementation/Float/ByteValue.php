<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Float;

use RuntimeException;

use function constant;
use function count;
use function defined;
use function pow;
use function preg_match;
use function sprintf;
use function strtoupper;

/**
 * @method static static fromB(float $bytes)
 * @method static static fromKB(float $kiloBytes)
 * @method static static fromMB(float $megaBytes)
 * @method static static fromGB(float $gigaBytes)
 * @method static static fromTB(float $terraBytes)
 * @method float toB()
 * @method float toKB()
 * @method float toMB()
 * @method float toGB()
 * @method float toTB()
 */
abstract class ByteValue extends FloatValue
{
    private const B = 0;
    private const KB = 1; // @phpstan-ignore-line
    private const MB = 2; // @phpstan-ignore-line
    private const GB = 3; // @phpstan-ignore-line
    private const TB = 4; // @phpstan-ignore-line

    /**
     * @param float[] $arguments
     *
     * @return static
     */
    public static function __callStatic(string $name, array $arguments): static
    {
        if (
            preg_match('#^from([A-Z]*B)#i', $name, $matches)
            && defined('self::' . strtoupper($matches[1]))
            && count($arguments) === 1
        ) {
            /** @var int $unit */
            $unit = constant('self::' . strtoupper($matches[1]));

            return self::from($unit, ...$arguments);
        }

        throw new RuntimeException(
            sprintf('No method \'%s\' found for class \'%s\'.', $name, static::class)
        );
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): float
    {
        if (
            preg_match('#^to([A-Z]*B)#i', $name, $matches)
            && defined('self::' . strtoupper($matches[1]))
            && count($arguments) === 0
        ) {
            /** @var int $unit */
            $unit = constant('self::' . strtoupper($matches[1]));

            return $this->to($unit);
        }

        throw new RuntimeException(
            sprintf('No method \'%s\' found for class \'%s\'.', $name, static::class)
        );
    }

    private static function from(int $unit, float $value): static
    {
        return self::fromFloat($value * pow(1024, $unit - self::B));
    }

    private function to(int $unit): float
    {
        return $this->toFloat() * pow(1024, self::B - $unit);
    }
}
