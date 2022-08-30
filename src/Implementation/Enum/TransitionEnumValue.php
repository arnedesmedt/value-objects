<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum;

use RuntimeException;

use function array_key_exists;
use function in_array;
use function sprintf;

abstract class TransitionEnumValue extends EnumValue
{
    /**
     * @return array<string,array<string>>
     */
    abstract public static function transitions(): array;

    abstract public static function init(): self;

    public function transitionTo(string $newValue): self
    {
        if (! array_key_exists($this->value, static::transitions())) {
            throw new RuntimeException(
                sprintf('No transition found from value \'%s\'.', $this->value)
            );
        }

        if (! in_array($newValue, static::transitions()[$this->value])) {
            throw new RuntimeException(
                sprintf('No transition found from value \'%s\' to value \'%s\'.', $this->value, $newValue)
            );
        }

        return static::fromValue($newValue);
    }
}
