<?php

namespace Paulo\Transform;

use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\ConvertOptions;
use Paulo\DataTransferObject;
use Paulo\Helpers\AttributeHelper;
use Paulo\Interfaces\GetterInterface;
use Paulo\Pipeline\GetPipeline;
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

                public function &__get(string $name)
                {
                    $value = null;
                    if ($this->property->getName() === $name && $name !== 'self') {
                         $value = $this->property->getValue($this->dto);
                        return $value;
                    }
                    return $value;
                }
            };
        }
        $attributes = $this->property->getAttributes(
            GetTransformable::class,
            \ReflectionAttribute::IS_INSTANCEOF);
        if ($this->options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $this->options);
        }
        return (new GetPipeline($dto, $this->property->getName()))
            ->getWithAttributes(
                $attributes
            );
    }
}