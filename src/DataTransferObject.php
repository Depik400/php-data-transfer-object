<?php

namespace Paulo;

use Paulo\Attributes\Interfaces\AttributePropertyBoth;
use Paulo\Attributes\Interfaces\AttributePropertyParseInterface;
use Paulo\Attributes\Interfaces\AttributePropertySerializeInterface;
use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Object\Arr;
use Paulo\Pipeline\ParsePipeline;
use Paulo\Pipeline\Pipeline;
use Paulo\Pipeline\SerializePipeline;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use function array_merge;

class DataTransferObject
{
    /**
     *
     * @param array<string,mixed>|DataTransferObject $wrap
     * @return static
     */
    public static function wrap(array|DataTransferObject $wrap): static
    {
        //@phpstan-ignore-next-line
        return (new static)->fill($wrap);
    }

    /**
     * @param array<string,mixed>|DataTransferObject $fromFill
     * @return static
     */
    public function fill(array|DataTransferObject $fromFill): static
    {
        if ($fromFill instanceof DataTransferObject) {
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
     * @param ReflectionProperty  $property
     * @param array<string,mixed> $wrap
     * @return void
     */
    protected function processProperty(ReflectionProperty $property, array $wrap): void
    {
        $attributes = $this->getParseAttributes($property);
        $pipeline = $this->getParsePipeline();
        $pipeline
            ->source($this->getArrGetter(new Arr($wrap), $property))
            ->destination($this->getObjectSetter($this, $property))
            ->property($property);
        $pipeline->pipeAttributes($attributes);
    }

    /**
     * @param ReflectionProperty $property
     * @return ReflectionAttribute<AttributePropertyBoth|AttributePropertyParseInterface>[]
     */
    protected function getParseAttributes(ReflectionProperty $property): array
    {
        return array_merge(
            $property->getAttributes(AttributePropertyBoth::class, ReflectionAttribute::IS_INSTANCEOF),
            $property->getAttributes(AttributePropertyParseInterface::class, ReflectionAttribute::IS_INSTANCEOF),
        );
    }

    /**
     * @return array<string, mixed>
     */
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

    /**
     * @param ReflectionProperty $property
     * @return ReflectionAttribute<AttributePropertyBoth|AttributePropertySerializeInterface>[]
     */
    protected function getSerializeAttributes(ReflectionProperty $property): array
    {
        return array_merge(
            $property->getAttributes(AttributePropertyBoth::class, ReflectionAttribute::IS_INSTANCEOF),
            $property->getAttributes(AttributePropertySerializeInterface::class, ReflectionAttribute::IS_INSTANCEOF),
        );
    }

    /**
     * @param DataTransferObject $source
     * @param ReflectionProperty $property
     * @return ObjectGetter
     */
    protected function getObjectGetter(mixed $source, ReflectionProperty $property): GetterInterface
    {
        return new ObjectGetter($source, $property);
    }

    /**
     * @param Arr|DataTransferObject $source
     * @param ReflectionProperty     $property
     * @return ObjectSetter
     */
    protected function getObjectSetter(mixed $source, ReflectionProperty $property): SetterInterface
    {
        return new ObjectSetter($source, $property);
    }

    /**
     * @param mixed              $source
     * @param ReflectionProperty $property
     * @return ArrGetter
     */
    protected function getArrGetter(mixed $source, ReflectionProperty $property): GetterInterface
    {
        return (new ArrGetter($source, $property));
    }

    /**
     * @param Arr|DataTransferObject $source
     * @param ReflectionProperty     $property
     * @return ArrSetter
     */
    protected function getArrSetter(mixed $source, ReflectionProperty $property): SetterInterface
    {
        return new ArrSetter($source, $property);
    }

    /**
     * @return Pipeline<ObjectGetter,ArrSetter>
     */
    protected function getSerializePipeline(): Pipeline
    {
        return new SerializePipeline();
    }

    /**
     * @return Pipeline<ArrGetter,ObjectSetter>
     */
    protected function getParsePipeline(): Pipeline
    {
        return new ParsePipeline();
    }
}
