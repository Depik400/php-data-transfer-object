<?php

namespace Paulo\Transform;

use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\ConvertOptions;
use Paulo\Helpers\AttributeHelper;
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
        $attributes = $this->property->getAttributes(
            SetTransformable::class,
            \ReflectionAttribute::IS_INSTANCEOF);
        if ($this->options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $this->options);
        }
        (new SetPipeline($this->dto, $this->property->getName()))
            ->setWithAttributes(
                $value,
                $attributes,
            );
    }
}
