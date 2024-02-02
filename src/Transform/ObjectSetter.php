<?php

namespace Paulo\Transform;

use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\ConvertOptions;
use Paulo\DataTransferObject;
use Paulo\Helpers\AttributeHelper;
use Paulo\Interfaces\SetterInterface;
use Paulo\Object\ProxyObject;
use Paulo\Pipeline\SetPipeline;
use Paulo\Reflect\Reflect;
use ReflectionProperty;

/**
 * @implements SetterInterface<DataTransferObject>
 */
class ObjectSetter implements SetterInterface
{
    protected object $dto;

    public function __construct(
        object                       $dto,
        protected ReflectionProperty $property,
        protected ?ConvertOptions    $options = null,
    )
    {
        if (!$this->property->isPublic() && $dto instanceof DataTransferObject) {
            $this->dto = new ProxyObject($dto, $this->property);
        } else {
            $this->dto = $dto;
        }
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
