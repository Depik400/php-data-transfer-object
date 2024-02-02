<?php

namespace Paulo\Object;

use ReflectionProperty;

class ProxyObject
{


    public function __construct(
        protected object             $dto,
        protected ReflectionProperty $property
    )
    {
    }

    public function __set(string $name, $value): void
    {
        $this->property->setValue($this->dto, $value);
    }

    public function &__get(string $name)
    {
        $value = null;
        if ($this->property->getName() === $name && $name !== 'self' && $this->property->isInitialized($this->dto)) {
            $value = $this->property->getValue($this->dto);
            return $value;
        }
        return $value;
    }
}