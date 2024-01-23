<?php

namespace Paulo;

use Paulo\Interfaces\SetterInterface;
use Paulo\Object\Arr;

/**
 * @implements SetterInterface<Arr>
 */
class ArrSetter implements SetterInterface
{
    protected Arr $dto;

    protected \ReflectionProperty $property;

    public function __construct(
        protected Arr$object,
        protected \ReflectionProperty $reflectionProperty
    )
    {
    }

    public function setDestination($object): static
    {
        $this->dto = $object;
        return $this;
    }

    public function setProperty(\ReflectionProperty $reflectionProperty): static
    {
        $this->property = $reflectionProperty;
        return $this;
    }

    public function set($value)
    {
        $this->dto[$this->property->getName()] = $value;
    }
}
