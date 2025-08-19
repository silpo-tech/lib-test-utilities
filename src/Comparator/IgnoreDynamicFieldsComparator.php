<?php

declare(strict_types=1);

namespace SilpoTech\Lib\TestUtilities\Comparator;

use SebastianBergmann\Comparator\ObjectComparator;

class IgnoreDynamicFieldsComparator extends ObjectComparator
{
    public function __construct(
        private readonly array $classes = [],
        private readonly array $properties = ['createdAt', 'updatedAt'],
    ) {
    }

    #[\Override]
    public function accepts(mixed $expected, mixed $actual): bool
    {
        foreach ($this->classes as $class) {
            if ($expected instanceof $class && $actual instanceof $class) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    protected function toArray(object $object): array
    {
        $array = parent::toArray($object);
        foreach ($this->properties as $property) {
            unset($array[$property]);
        }

        return $array;
    }
}
