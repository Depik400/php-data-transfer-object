<?php

namespace Paulo;

use ReflectionProperty;
use Paulo\Interfaces\GetterInterface;

/**
 * @implements GetterInterface<DataTransferObject>
 */
class ObjectGetter implements GetterInterface
{
    protected DataTransferObject $dto;

    protected ReflectionProperty $property;

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


    public function get()
    {
        return $this->property->getValue($this->dto);

    }
}