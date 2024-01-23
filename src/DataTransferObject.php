<?php

namespace Paulo;

use Reflection;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Paulo\Attributes\Interfaces\AttributePropertyBoth;
use Paulo\Attributes\Interfaces\AttributePropertyParseInterface;
use Paulo\Attributes\Interfaces\AttributePropertySerializeInterface;
use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Object\Arr;

class DataTransferObject
{
    /**
     *
     * @param array<string,mixed>|DataTransferObject $wrap
     * @return static
     */
    public static function wrap(array|DataTransferObject $wrap): static
    {
        return (new static)->fill($wrap);
    }

    /**
     * @param array<string,mixed>|DataTransferObject $fromFill
     * @return static
     */
    public function fill(array|DataTransferObject $fromFill): static
    {
        if($fromFill instanceof DataTransferObject) {
            $fromFill = $fromFill->toArray();
        }
        $reflector = new ReflectionClass(static::class);
        $properties = $reflector->getProperties();
        foreach ($properties as $property) {
            $this->processProperty($property, $fromFill);
        }
        return $this;
    }


    /**
     *@param ReflectionProperty $property
     * @param array<string,mixed> $wrap
     * @return void
     */
    protected function processProperty(ReflectionProperty $property, array $wrap): void
    {
        $attributes = \array_merge(
            $property->getAttributes(AttributePropertyBoth::class, ReflectionAttribute::IS_INSTANCEOF),
            $property->getAttributes(AttributePropertyParseInterface::class,  ReflectionAttribute::IS_INSTANCEOF),
        );
        $pipeline = $this->getParsePipeline();
        $pipeline
            ->source($this->getArrGetter(new Arr($wrap), $property))
            ->destination($this->getObjectSetter($this, $property))
            ->property($property);
        $pipeline->pipeAttributes($attributes);
    }

    public function toArray(): array
    {
        $result = new Arr();
        $reflector = new ReflectionClass($this);
        $properties = $reflector->getProperties();
        foreach ($properties as $property) {
            $this->processSerializeProperty($property, $result);
        }
        return $result->getArray();
    }

    protected function processSerializeProperty(ReflectionProperty $property, Arr $result): void
    {
        $attributes = $this->getSerializeAttributes($property);
        $pipeline = $this->getSerializePipeline();
        $pipeline
            ->source($this->getObjectGetter($this, $property))
            ->destination($this->getArrSetter($result, $property))
            ->property($property);
        $pipeline->pipeAttributes($attributes);
    }

    protected function getSerializeAttributes(ReflectionProperty $property) {
        return \array_merge(
            $property->getAttributes(AttributePropertyBoth::class, ReflectionAttribute::IS_INSTANCEOF),
            $property->getAttributes(AttributePropertySerializeInterface::class,  ReflectionAttribute::IS_INSTANCEOF),
        );
    }

    protected function getObjectGetter($source, ReflectionProperty $property): GetterInterface {
        return (new ObjectGetter())->setSource($source)->setProperty($property);
    }

    protected function getObjectSetter(Arr|DataTransferObject $source, ReflectionProperty $property): SetterInterface {
        return (new ObjectSetter())->setDestination($source)->setProperty($property);
    }

    protected function getArrGetter($source, ReflectionProperty $property): GetterInterface {
        return (new ArrGetter())->setSource($source)->setProperty($property);
    }

    protected function getArrSetter(Arr|DataTransferObject $source, ReflectionProperty $property): SetterInterface {
        return (new ArrSetter())->setDestination($source)->setProperty($property);
    }
    /**
     * Undocumented function
     *
     * @return Pipeline
     */
    protected function getSerializePipeline() {
        return new SerializePipeline();
    }

    /**
     * Undocumented function
     *
     * @return Pipeline
     */
    protected function getParsePipeline() {
        return new ParsePipeline();
    }
}
