<?php

namespace Paulo\Transform;

use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\ConvertOptions;
use Paulo\DataTransferObject;
use Paulo\Helpers\AttributeHelper;
use Paulo\Interfaces\SetterInterface;
use Paulo\Pipeline\SetPipeline;
use ReflectionProperty;

/**
 * @implements SetterInterface<DataTransferObject>
 */
class ObjectSetter implements SetterInterface
{


    public function __construct(
        protected object             $dto,
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
        $dto = $this->dto;
        if ($this->property->isProtected() && $this->dto instanceof DataTransferObject) {
            $dto = new class($this->dto, $this->property) {
                public function __construct(
                    protected                    $dto,
                    protected ReflectionProperty $property
                )
                {
                }

                public function __set(string $name, $value): void
                {
                    $this->property->setValue($this->dto, $value);
                }

                public function __get(string $name)
                {
                    if ($this->property->getName() === $name)
                        return $this->property->getValue($this->dto);
                    return null;
                }
            };
        }
        $attributes = $this->property->getAttributes(
            SetTransformable::class,
            \ReflectionAttribute::IS_INSTANCEOF);
        if ($this->options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $this->options);
        }
        (new SetPipeline($dto, $this->property->getName()))
            ->setWithAttributes(
                $value,
                $attributes,
            );
    }
}
