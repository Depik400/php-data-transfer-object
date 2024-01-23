<?php

namespace Paulo\Transform;

use Paulo\Interfaces\SetterInterface;
use Paulo\Object\Arr;
use ReflectionProperty;

/**
 * @implements SetterInterface<Arr>
 */
class ArrSetter implements SetterInterface
{
    public function __construct(
        protected Arr $dto,
        protected ReflectionProperty $property,
    )
    {
    }

    public function setDestination($object): static
    {
        $this->dto = $object;
        return $this;
    }

    public function setProperty(ReflectionProperty $reflectionProperty): static
    {
        $this->property = $reflectionProperty;
        return $this;
    }

    public function set(mixed $value): void
    {
        $this->dto[$this->property->getName()] = $value;
    }
}
