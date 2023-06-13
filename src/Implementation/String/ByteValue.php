<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\String;

use RuntimeException;

use function constant;
use function count;
use function defined;
use function in_array;
use function preg_match;
use function sprintf;
use function strtoupper;

/**
 * @method static static fromB(float $bytes)
 * @method static static fromK(float $kiloBytes)
 * @method static static fromM(float $megaBytes)
 * @method static static fromG(float $gigaBytes)
 * @method static static fromT(float $terraBytes)
 * @method float toB()
 * @method float toK()
 * @method float toM()
 * @method float toG()
 * @method float toT()
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
abstract class ByteValue extends StringValue
{
    private const B = 0;
    private const K = 1;
    private const M = 2;
    private const G = 3;
    private const T = 4;

    private const POSSIBLE_UNITS = [
        self::B,
        self::K,
        self::M,
        self::G,
        self::T,
    ];

    private const POSSIBLE_MODIFIERS = [
        'B',
        'K',
        'M',
        'G',
        'T',
    ];

    public static function fromString(string $value): static
    {
        if (
            ! preg_match(
                '/^(?P<value>[0-9]+([\.,][0-9]+)?)\s*(?P<modifier>B|b|K|k|M|m|G|g|T|t)?$/',
                $value,
                $matches,
            )
        ) {
            throw new RuntimeException(
                sprintf(
                    'Invalid byte pattern given \'%s\'.',
                    $value
                )
            );
        }

        if (! isset($matches['modifier'])) {
            $matches['modifier'] = self::POSSIBLE_MODIFIERS[self::inputUnit()];
        }

        /** @var int $unit */
        $unit = constant('self::' . strtoupper($matches['modifier']));

        return self::fromUnit($unit, $matches['value']);
    }

    /**
     * @param string[] $arguments
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

            return self::fromUnit($unit, ...$arguments);
        }

        throw new RuntimeException(
            sprintf('No method \'%s\' found for class \'%s\'.', $name, static::class)
        );
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): string
    {
        if (
            preg_match('#^to([A-Z]*B)#i', $name, $matches)
            && defined('self::' . strtoupper($matches[1]))
            && count($arguments) === 0
        ) {
            /** @var int $unit */
            $unit = constant('self::' . strtoupper($matches[1]));

            return $this->toUnit($unit);
        }

        throw new RuntimeException(
            sprintf('No method \'%s\' found for class \'%s\'.', $name, static::class)
        );
    }

    private static function fromUnit(int $unit, string $value): static
    {
        return new static((string) ((float) $value * 1024 ** ($unit - self::B)));
    }

    private function toUnit(int $unit): string
    {
        return sprintf(
            '%s%s',
            (string) ((int) parent::toString() * 1024 ** (self::B - $unit)),
            self::POSSIBLE_MODIFIERS[$unit]
        );
    }

    public function toString(): string
    {
        return $this->toUnit(self::checkUnit(self::outputUnit()));
    }

    protected static function inputUnit(): int
    {
        return self::M;
    }

    protected static function outputUnit(): int
    {
        return self::M;
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

    public static function example(): static
    {
        return self::fromString('12M');
    }
}
