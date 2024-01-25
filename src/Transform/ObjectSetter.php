<?php

namespace Paulo\Transform;

use Paulo\ConvertOptions;
use Paulo\DataTransferObject;
use Paulo\Interfaces\SetterInterface;
use ReflectionProperty;

/**
 * @implements SetterInterface<DataTransferObject>
 */
class ObjectSetter implements SetterInterface
{


    public function __construct(
        protected DataTransferObject $dto,
        protected ReflectionProperty $property,
        protected ?ConvertOptions    $options = null,
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
        $this->property->setValue($this->dto, $value);
    }
}
