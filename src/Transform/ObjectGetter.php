<?php

namespace Paulo\Transform;

use Paulo\ConvertOptions;
use Paulo\DataTransferObject;
use Paulo\Interfaces\GetterInterface;
use ReflectionProperty;

/**
 * @implements GetterInterface<DataTransferObject>
 */
class ObjectGetter implements GetterInterface
{
    public function __construct(
        protected DataTransferObject $dto,
        protected ReflectionProperty $property,
        protected ?ConvertOptions $options = null,
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

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        if ($this->property->isInitialized($this->dto))
            return $this->property->getValue($this->dto);
        return null;
    }
}