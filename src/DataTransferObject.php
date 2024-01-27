<?php

namespace Paulo;

use Paulo\Attributes\Abstract\Transformable;
use Paulo\Attributes\Interfaces\AttributePropertyBoth;
use Paulo\Attributes\Interfaces\AttributePropertyParseInterface;
use Paulo\Attributes\Interfaces\AttributePropertySerializeInterface;
use Paulo\Helpers\AttributeHelper;
use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Object\Arr;
use Paulo\Pipeline\ParsePipeline;
use Paulo\Pipeline\Pipeline;
use Paulo\Pipeline\SerializePipeline;
use Paulo\Transform\ArrGetter;
use Paulo\Transform\ArrSetter;
use Paulo\Transform\ObjectGetter;
use Paulo\Transform\ObjectSetter;
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
    public static function wrap(array|DataTransferObject $wrap, ?ConvertOptions $options = null): static
    {
        //@phpstan-ignore-next-line
        return (new static())->fill($wrap, $options);
    }

    /**
     * @param array<string,mixed>|DataTransferObject $fromFill
     * @return static
     */
    public function fill(array|DataTransferObject $fromFill, ?ConvertOptions $options = null): static
    {
        if (!is_array($fromFill)) {
            $getter = fn ($property) => $this->getObjectGetter($fromFill, $property);
        } else {
            $arr = new Arr($fromFill);
            $getter = fn ($property) => $this->getArrGetter($arr, $property, $options);
        }
        $reflector = new ReflectionClass(static::class);
        $properties = $reflector->getProperties();
        $pipeline = $this->getParsePipeline();
        foreach ($properties as $property) {
            $attributes = $this->getParseAttributes($property, $options);
            $this->process(
                $attributes,
                $property,
                $pipeline,
                $getter($property),
                $this->getObjectSetter($this, $property, $options),
                $options,
            );
        }
        return $this;
    }

    public function clone(): static
    {
        return (new static())->fill($this);
    }


    /**
     * @param ReflectionProperty $property
     * @return ReflectionAttribute<(AttributePropertyBoth&Transformable)|(AttributePropertyParseInterface&Transformable)>[]
     */
    protected function getParseAttributes(ReflectionProperty $property, ?ConvertOptions $options = null): array
    {
        $attributes = array_merge(
            $property->getAttributes(AttributePropertyBoth::class, ReflectionAttribute::IS_INSTANCEOF),
            $property->getAttributes(AttributePropertyParseInterface::class, ReflectionAttribute::IS_INSTANCEOF),
        );
        if ($options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $options);
        }
        return $attributes;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(?ConvertOptions $options = null): array
    {
        $result = new Arr();
        $reflector = new ReflectionClass($this);
        $properties = $reflector->getProperties();
        $pipeline = $this->getSerializePipeline();
        foreach ($properties as $property) {
            $attributes = $this->getSerializeAttributes($property, $options);
            $this->process(
                $attributes,
                $property,
                $pipeline,
                $this->getObjectGetter($this, $property, $options),
                $this->getArrSetter($result, $property, $options),
                $options
            );
        }
        return $result->getArray();
    }

    /**
     * @param array<string|int, mixed> $from
     * @param ConvertOptions|null      $options
     * @return array<string|int, mixed>
     */
    public static function convert(array $from, ?ConvertOptions $options = null): array
    {
        return (new static())->toArrayFromArray($from, $options);
    }

    /**
     * @param array<string|int, mixed> $from
     * @param ConvertOptions|null      $options
     * @return array
     */
    public function toArrayFromArray(array $from, ?ConvertOptions $options = null): array
    {
        $result = new Arr();
        $from = new Arr($from);
        $reflector = new ReflectionClass($this);
        $properties = $reflector->getProperties();
        $pipeline = $this->getSerializePipeline();
        foreach ($properties as $property) {
            $attributes = $this->getSerializeAttributes($property, $options);
            $this->process(
                $attributes,
                $property,
                $pipeline,
                $this->getArrGetter($from, $property, $options),
                $this->getArrSetter($result, $property, $options),
                $options,
            );
        }
        return $result->getArray();
    }


    /**
     * @param array               $attributes
     * @param ReflectionProperty  $property
     * @param Pipeline            $pipeline
     * @param GetterInterface     $getter
     * @param SetterInterface     $setter
     * @param ConvertOptions|null $options
     * @return void
     */
    protected function process(
        array              $attributes,
        ReflectionProperty $property,
        Pipeline           $pipeline,
        GetterInterface    $getter,
        SetterInterface    $setter,
        ?ConvertOptions    $options = null
    ) {
        if ($options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $options);
        }
        $pipeline
            ->source($getter)
            ->destination($setter)
            ->property($property);
        $pipeline->pipeAttributes($attributes);
    }

    /**
     * @param ReflectionProperty $property
     * @return ReflectionAttribute<(AttributePropertyBoth&Transformable)|(AttributePropertySerializeInterface&Transformable)>[]
     */
    protected function getSerializeAttributes(ReflectionProperty $property, ?ConvertOptions $options = null): array
    {
        $attributes = array_merge(
            $property->getAttributes(AttributePropertyBoth::class, ReflectionAttribute::IS_INSTANCEOF),
            $property->getAttributes(AttributePropertySerializeInterface::class, ReflectionAttribute::IS_INSTANCEOF),
        );
        if ($options) {
            $attributes = AttributeHelper::filterReflectionAttributes($attributes, $options);
        }
        return $attributes;
    }

    /**
     * @param DataTransferObject $source
     * @param ReflectionProperty $property
     * @return ObjectGetter
     */
    protected function getObjectGetter(mixed $source, ReflectionProperty $property, ?ConvertOptions $options = null): GetterInterface
    {
        return new ObjectGetter($source, $property, $options);
    }

    /**
     * @param Arr|DataTransferObject $source
     * @param ReflectionProperty     $property
     * @return ObjectSetter
     */
    protected function getObjectSetter(mixed $source, ReflectionProperty $property, ?ConvertOptions $options = null): SetterInterface
    {
        return new ObjectSetter($source, $property, $options);
    }

    /**
     * @param mixed              $source
     * @param ReflectionProperty $property
     * @return ArrGetter
     */
    protected function getArrGetter(mixed $source, ReflectionProperty $property, ?ConvertOptions $options = null): GetterInterface
    {
        return (new ArrGetter($source, $property, $options));
    }

    /**
     * @param Arr|DataTransferObject $source
     * @param ReflectionProperty     $property
     * @return ArrSetter
     */
    protected function getArrSetter(mixed $source, ReflectionProperty $property, ?ConvertOptions $options = null): SetterInterface
    {
        return new ArrSetter($source, $property, $options);
    }

    /**
     * @return Pipeline<GetterInterface,SetterInterface>
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
