<?php

declare(strict_types=1);

namespace ADS\ValueObjects\Implementation\Enum;

use RuntimeException;

use function array_filter;
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

    public function next(self $newValue): self
    {
        if (! array_key_exists($this->value, static::transitions())) {
            throw new RuntimeException(
                sprintf('No transition found from value \'%s\'.', $this->value)
            );
        }

        if (! in_array($newValue->toValue(), static::transitions()[$this->value])) {
            throw new RuntimeException(
                sprintf(
                    'No transition found from value \'%s\' to value \'%s\'.',
                    $this->value,
                    $newValue
                )
            );
        }

        return $newValue;
    }

    public function previous(self $newValue): self
    {
        $validTransitions = array_filter(
            static::transitions(),
            fn (array $to) => in_array($this->value, $to)
        );

        if (empty($validTransitions)) {
            throw new RuntimeException(
                sprintf('No reverse transition found from value \'%s\'.', (string) $this->value)
            );
        }

        if (! array_key_exists((string) $newValue, $validTransitions)) {
            throw new RuntimeException(
                sprintf(
                    'No reverse transition found from value \'%s\' to value \'%s\'.',
                    (string) $this->value,
                    $newValue
                )
            );
        }

        return $newValue;
    }
}
