<?php

namespace Paulo\Transform;

use Paulo\Attributes\Interfaces\AttributePropertySetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Object\Arr;
use Paulo\Pipeline\SetPipeline;
use ReflectionProperty;

/**
 * @implements SetterInterface<Arr>
 */
class ArrSetter implements SetterInterface
{
    public function __construct(
        protected Arr                $dto,
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
        (new SetPipeline($this->dto, $this->property->getName()))
            ->setWithAttributes(
                $value,
                $this->property->getAttributes(
                    AttributePropertySetterInterface::class,
                    \ReflectionAttribute::IS_INSTANCEOF
                )
            );
    }
}
