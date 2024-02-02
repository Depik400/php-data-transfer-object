<?php

namespace Paulo\Transform;

use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\ConvertOptions;
use Paulo\DataTransferObject;
use Paulo\Helpers\AttributeHelper;
use Paulo\Interfaces\GetterInterface;
use Paulo\Object\ProxyObject;
use Paulo\Pipeline\GetPipeline;
use Paulo\Reflect\Reflect;
use ReflectionProperty;

/**
 * @implements GetterInterface<DataTransferObject>
 */
class ObjectGetter implements GetterInterface
{
    protected object $dto;

    public function __construct(
        object                       $dto,
        protected ReflectionProperty $property,
        protected ?ConvertOptions    $options = null,
    )
    {
        if (!$this->property->isPublic() && !($dto instanceof \stdClass)) {
            $this->dto = new ProxyObject($dto,
                $this->property->class === get_class($dto) ?
                    $this->property :
                    Reflect::getPropertyByName($dto, $this->property->getName())
            );
        } else {
            $this->dto = $dto;
        }
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
        $attributes = $this->property->getAttributes(
            GetTransformable::class, \ReflectionAttribute::IS_INSTANCEOF);
        if ($this->options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $this->options);
        }
        return (new GetPipeline($this->dto, $this->property->getName()))
            ->getWithAttributes(
                $attributes
            );
    }
}