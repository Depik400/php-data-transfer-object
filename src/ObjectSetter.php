<?php

namespace Paulo;

use Paulo\Interfaces\SetterInterface;
use Paulo\Object\Arr;

/**
 * @implements SetterInterface<DataTransferObject>
 */
class ObjectSetter implements SetterInterface
{


    public function __construct(
        protected DataTransferObject  $dto,
        protected \ReflectionProperty $property,
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
        $this->property->setValue($this->dto, $value);
    }
}
