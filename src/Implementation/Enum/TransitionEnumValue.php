<?php

declare(strict_types=1);

namespace TeamBlue\ValueObjects\Implementation\Enum;

use LogicException;

use function array_filter;
use function array_key_exists;
use function assert;
use function in_array;
use function sprintf;

abstract class TransitionEnumValue extends EnumValue
{
    /** @return array<string|int,array<string|int>> */
    abstract public static function transitions(): array;

    abstract public static function init(): static;

    public function next(self $newValue): static
    {
        assert($newValue instanceof static);

        if (! array_key_exists($this->value, static::transitions())) {
            throw new LogicException(
                sprintf('No transition found from value \'%s\'.', $this->value),
            );
        }

        if (! in_array($newValue->toValue(), static::transitions()[$this->value])) {
            throw new LogicException(
                sprintf(
                    'No transition found from value \'%s\' to value \'%s\'.',
                    $this->value,
                    $newValue,
                ),
            );
        }

        return $newValue;
    }

    public function previous(self $newValue): static
    {
        assert($newValue instanceof static);

        $validTransitions = array_filter(
            static::transitions(),
            fn (array $toValues) => in_array($this->value, $toValues),
        );

        if (empty($validTransitions)) {
            throw new LogicException(
                sprintf('No reverse transition found from value \'%s\'.', (string) $this->value),
            );
        }

        if (! array_key_exists((string) $newValue, $validTransitions)) {
            throw new LogicException(
                sprintf(
                    'No reverse transition found from value \'%s\' to value \'%s\'.',
                    (string) $this->value,
                    $newValue,
                ),
            );
        }

        return $newValue;
    }
}
