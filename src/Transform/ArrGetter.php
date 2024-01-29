<?php

namespace Paulo\Transform;

use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\ConvertOptions;
use Paulo\Helpers\AttributeHelper;
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
        protected ?ConvertOptions    $options = null,
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
        $attributes = $this->property->getAttributes(
            GetTransformable::class,
            \ReflectionAttribute::IS_INSTANCEOF);
        if ($this->options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $this->options);
        }
        $val = (new GetPipeline($this->dto, $this->property->getName()))
            ->getWithAttributes(
                $attributes
            );
        return $val;
    }
}