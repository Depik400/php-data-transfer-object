<?php

namespace Paulo\Transform;

use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\Interfaces\GetterInterface;
use Paulo\Object\Arr;
use Paulo\Pipeline\GetPipeline;
use ReflectionProperty;

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
        return (new GetPipeline($this->dto, $this->property->getName()))
            ->getWithAttributes($this->property->getAttributes(
                GetTransformable::class,
                \ReflectionAttribute::IS_INSTANCEOF)
            );
    }
}