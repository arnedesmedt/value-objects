<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\String;

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
    final public const B = 0;
    final public const K = 1;
    final public const M = 2;
    final public const G = 3;
    final public const T = 4;

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
                    $value,
                ),
            );
        }

        if (! isset($matches['modifier'])) {
            $matches['modifier'] = self::POSSIBLE_MODIFIERS[static::inputUnit()];
        }

        /** @var int $unit */
        $unit = constant('self::' . strtoupper($matches['modifier']));

        return self::fromUnit($unit, $matches['value']);
    }

    public static function fromInt(int $intValue): static
    {
        $modifier = self::POSSIBLE_MODIFIERS[static::inputUnit()];

        /** @var int $unit */
        $unit = constant('self::' . strtoupper($modifier));

        return self::fromUnit($unit, (string) $intValue);
    }

    public static function fromFloat(float $floatValue): static
    {
        $modifier = self::POSSIBLE_MODIFIERS[static::inputUnit()];

        /** @var int $unit */
        $unit = constant('self::' . strtoupper($modifier));

        return self::fromUnit($unit, (string) $floatValue);
    }

    /**
     * @param string[] $arguments
     *
     * @return static
     */
    public static function __callStatic(string $name, array $arguments): static
    {
        if (
            preg_match('#^from([A-Z]*)#i', $name, $matches)
            && defined('self::' . strtoupper($matches[1]))
            && count($arguments) === 1
        ) {
            /** @var int $unit */
            $unit = constant('self::' . strtoupper($matches[1]));

            return self::fromUnit($unit, ...$arguments);
        }

        throw new RuntimeException(
            sprintf('No method \'%s\' found for class \'%s\'.', $name, static::class),
        );
    }

    /** @param array<mixed> $arguments */
    public function __call(string $name, array $arguments): float
    {
        if (
            preg_match('#^to([A-Z]*)#i', $name, $matches)
            && defined('self::' . strtoupper($matches[1]))
            && count($arguments) === 0
        ) {
            /** @var int $unit */
            $unit = constant('self::' . strtoupper($matches[1]));

            return $this->toUnit($unit);
        }

        throw new RuntimeException(
            sprintf('No method \'%s\' found for class \'%s\'.', $name, static::class),
        );
    }

    private static function fromUnit(int $unit, string $value): static
    {
        return new static((string) ((float) $value * 1024 ** ($unit - self::B)));
    }

    private function toUnit(int $unit): float
    {
        return (float) ((int) parent::toString() * 1024 ** (self::B - $unit));
    }

    public function toString(): string
    {
        $unit = self::checkUnit(static::outputUnit());

        return sprintf(
            '%s%s',
            (string) $this->toUnit($unit),
            self::POSSIBLE_MODIFIERS[$unit],
        );
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
                sprintf('Input unit \'%d\' is not in the list of valid units.', $unit),
            );
        }

        return $unit;
    }

    public static function example(): static
    {
        return self::fromString('12M');
    }
}
