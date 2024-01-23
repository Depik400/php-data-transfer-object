<?php

namespace Paulo;

use ReflectionProperty;
use Paulo\Interfaces\GetterInterface;
use Paulo\Object\Arr;

/**
 * @implements GetterInterface<Arr>
 */
class ArrGetter implements GetterInterface
{
    public function __construct(
        protected Arr                $dto,
        protected ReflectionProperty $property,
    )
    {
    }

    public function setSource($object): static
    {
        $this->dto = $object;
        return $this;
    }


    public function setProperty(ReflectionProperty $reflectionProperty): static
    {
        $this->property = $reflectionProperty;
        return $this;
    }


    public function get(): mixed
    {
        return $this->dto[$this->property->getName()];
    }
}