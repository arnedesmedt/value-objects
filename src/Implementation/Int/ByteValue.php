<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Int;

use RuntimeException;

use function constant;
use function count;
use function defined;
use function in_array;
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
abstract class ByteValue extends IntValue
{
    private const B = 0;
    private const KB = 1;
    private const MB = 2;
    private const GB = 3;
    private const TB = 4;

    private const POSSIBLE_UNITS = [
        self::B,
        self::KB,
        self::MB,
        self::GB,
        self::TB,
    ];

    public static function fromString(string $value): void
    {
        if (! preg_match('/^(?P<value>[0-9]+([\.,][0-9]+)?)(?P<modifier>K|k|M|m|G|g|T|t)?(b|B)?$/', $value, $matches)) {
            throw new RuntimeException(
                sprintf(
                    'Invalid byte pattern given \'%s\'.',
                    $value
                )
            );
        }

        if (! isset($matches['modifier'])) {
            $matches['modifier'] = '';
        }

        /** @var int $unit */
        $unit = constant('self::' . strtoupper($matches['modifier']) . 'B');

        self::from($unit, (float) $matches['value']);
    }

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
        return parent::fromInt((int) $value * pow(1024, $unit - self::B));
    }

    private function to(int $unit): float
    {
        return parent::toInt() * pow(1024, self::B - $unit);
    }

    public static function fromFloat(float $value): static
    {
        return self::from(self::checkUnit(self::inputUnit()), $value);
    }

    public function toFloat(): float
    {
        return $this->to(self::checkUnit(self::outputUnit()));
    }

    protected static function inputUnit(): int
    {
        return self::MB;
    }

    protected static function outputUnit(): int
    {
        return self::MB;
    }

    private static function checkUnit(int $unit): int
    {
        if (! in_array($unit, self::POSSIBLE_UNITS)) {
            throw new RuntimeException(
                sprintf('Input unit \'%d\' is not in the list of valid units.', $unit)
            );
        }

        return $unit;
    }
}
