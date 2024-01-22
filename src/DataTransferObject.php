<?php

namespace Svyazcom\DataTransferObject;

use Reflection;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Svyazcom\DataTransferObject\Attributes\Interfaces\AttributePropertyBoth;
use Svyazcom\DataTransferObject\Attributes\Interfaces\AttributePropertyGetterInterface;
use Svyazcom\DataTransferObject\Attributes\Interfaces\AttributePropertyParseInterface;
use Svyazcom\DataTransferObject\Attributes\Interfaces\AttributePropertySerializeInterface;
use Svyazcom\DataTransferObject\Interfaces\GetterInterface;
use Svyazcom\DataTransferObject\Interfaces\SetterInterface;
use Svyazcom\DataTransferObject\Object\Arr;

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
        $pipeline->source($this->getGetter($wrap, $property))->property($property)->destination($this->getSetter($this, $property));
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
        $pipeline->property($property)->source($this->getGetter($this, $property))->destination($this->getSetter($result, $property));
        $pipeline->pipeAttributes($attributes);
    }

    protected function getSerializeAttributes(ReflectionProperty $property) {
        return \array_merge(
            $property->getAttributes(AttributePropertyBoth::class, ReflectionAttribute::IS_INSTANCEOF),
            $property->getAttributes(AttributePropertySerializeInterface::class,  ReflectionAttribute::IS_INSTANCEOF),
        );
    }

    protected function getGetter($source, ReflectionProperty $property): GetterInterface {
        return (new Getter())->setSource($source)->setProperty($property);
    }

    protected function getSetter(Arr|DataTransferObject $source, ReflectionProperty $property): SetterInterface {
        return (new Setter())->setDestination($source)->setProperty($property);
    }
    /**
     * Undocumented function
     *
     * @return SerializePipeline
     */
    protected function getSerializePipeline() {
        return new SerializePipeline();
    }

    /**
     * Undocumented function
     *
     * @return ParsePipeline
     */
    protected function getParsePipeline() {
        return new ParsePipeline();
    }
}
