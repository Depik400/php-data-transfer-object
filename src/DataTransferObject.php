<?php

namespace Paulo;

use Paulo\Attributes\Abstract\Transformable;
use Paulo\Attributes\Interfaces\AttributePropertyBoth;
use Paulo\Attributes\Interfaces\AttributePropertyParseInterface;
use Paulo\Attributes\Interfaces\AttributePropertySerializeInterface;
use Paulo\Attributes\PropertyInternal;
use Paulo\Attributes\PropertyMapFrom;
use Paulo\Attributes\PropertyMapTo;
use Paulo\GetPipes\MapFromGetPipe;
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

class DataTransferObject implements \ArrayAccess
{
    #[PropertyInternal]
    protected ReflectionClass $self;

    public function __construct()
    {
        $this->self = new ReflectionClass($this);
    }

    /**
     *
     * @param array<string,mixed>|DataTransferObject $wrap
     * @param ConvertOptions|null                    $options
     * @return static
     */
    public static function wrap(array|DataTransferObject $wrap, ?ConvertOptions $options = null): static
    {
        //@phpstan-ignore-next-line
        return (new static())->fill($wrap, $options);
    }

    /**
     * @param array<string,mixed>|DataTransferObject $fromFill
     * @param ConvertOptions|null                    $options
     * @return static
     */
    public function fill(array|DataTransferObject $fromFill, ?ConvertOptions $options = null): static
    {
        if (!is_array($fromFill)) {
            $getter = fn($property) => $this->getObjectGetter($fromFill, $property);
        } else {
            $arr = new Arr($fromFill);
            $getter = fn($property) => $this->getArrGetter($arr, $property, $options);
        }
        $reflector = new ReflectionClass(static::class);
        $properties = $reflector->getProperties();
        $pipeline = $this->getParsePipeline();
        foreach ($properties as $property) {
            $attributes = $this->getParseAttributes($property, $options);
            $this->processParse(
                $attributes,
                $property,
                $pipeline,
                $getter($property),
                $this->getObjectSetter($this, $property, new ConvertOptions([PropertyMapTo::class])),
                $options,
            );
        }
        return $this;
    }

    public function clone(): static
    {
        //@phpstan-ignore-next-line
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
            $this->processSerialize(
                $attributes,
                $property,
                $pipeline,
                $this->getObjectGetter($this, $property, new ConvertOptions([PropertyMapFrom::class])),
                $this->getArrSetter($result, $property, $options),
                $options
            );
        }
        return $result->getArray();
    }

    /**
     * @param array<array-key, mixed> $from
     * @param ConvertOptions|null     $options
     * @return array<array-key, mixed>
     */
    public static function convert(array $from, ?ConvertOptions $options = null): array
    {
        return (new static())->toArrayFromArray($from, $options);
    }

    /**
     * @param array<array-key, mixed> $from
     * @param ConvertOptions|null     $options
     * @return array<array-key, mixed>
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
            $this->processArrayFromArray(
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

    public function copyTo(object|array &$to, ConvertOptions $options = null): void
    {
        if (is_object($to)) {
            $setter = fn($property) => $this->getObjectSetter($to, $property, $options);
        } else {
            $arr = (new Arr())->wrapRef($to);
            $setter = fn($property) => $this->getArrSetter($arr, $property, $options);
        }
        $reflector = new ReflectionClass($this);
        $properties = $reflector->getProperties();
        $pipeline = $this->getSerializePipeline();
        foreach ($properties as $property) {
            $attributes = $this->getSerializeAttributes($property, $options);
            $this->processArrayFromArray(
                $attributes,
                $property,
                $pipeline,
                $this->getObjectGetter($this, $property, $options),
                $setter($property),
                $options,
            );
        }
    }


    /**
     * @template TGetter of GetterInterface
     * @template TSetter of SetterInterface
     * @param ReflectionAttribute<Transformable>[] $attributes
     * @param ReflectionProperty                   $property
     * @param Pipeline<TGetter,TSetter>            $pipeline
     * @param TGetter                              $getter
     * @param TSetter                              $setter
     * @param ConvertOptions|null                  $options
     * @return void
     */
    protected function process(
        array              $attributes,
        ReflectionProperty $property,
        Pipeline           $pipeline,
        GetterInterface    $getter,
        SetterInterface    $setter,
        ?ConvertOptions    $options = null
    ): void
    {
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
     * @param ReflectionAttribute<Transformable>[]                       $attributes
     * @param ReflectionProperty                                         $property
     * @param Pipeline<GetterInterface<object>, SetterInterface<object>> $pipeline
     * @param GetterInterface<object>                                    $getter
     * @param SetterInterface<object>                                    $setter
     * @param ConvertOptions|null                                        $options
     * @return void
     */
    protected function processParse(
        array              $attributes,
        ReflectionProperty $property,
        Pipeline           $pipeline,
        GetterInterface    $getter,
        SetterInterface    $setter,
        ?ConvertOptions    $options = null
    ): void
    {
        $this->process(...func_get_args());
    }

    /**
     * @param ReflectionAttribute<Transformable>[]                       $attributes
     * @param ReflectionProperty                                         $property
     * @param Pipeline<GetterInterface<object>, SetterInterface<object>> $pipeline
     * @param GetterInterface<object>                                    $getter
     * @param SetterInterface<object>                                    $setter
     * @param ConvertOptions|null                                        $options
     * @return void
     */
    protected function processSerialize(
        array              $attributes,
        ReflectionProperty $property,
        Pipeline           $pipeline,
        GetterInterface    $getter,
        SetterInterface    $setter,
        ?ConvertOptions    $options = null
    ): void
    {
        $this->process(...func_get_args());
    }

    /**
     * @param ReflectionAttribute<Transformable>[]                       $attributes
     * @param ReflectionProperty                                         $property
     * @param Pipeline<GetterInterface<object>, SetterInterface<object>> $pipeline
     * @param GetterInterface<object>                                    $getter
     * @param SetterInterface<object>                                    $setter
     * @param ConvertOptions|null                                        $options
     * @return void
     */
    protected function processArrayFromArray(
        array              $attributes,
        ReflectionProperty $property,
        Pipeline           $pipeline,
        GetterInterface    $getter,
        SetterInterface    $setter,
        ?ConvertOptions    $options = null
    ): void
    {
        $this->process(...func_get_args());
    }


    /**
     * @param ReflectionProperty  $property
     * @param ConvertOptions|null $options
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
     * @param DataTransferObject  $source
     * @param ReflectionProperty  $property
     * @param ConvertOptions|null $options
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
     * @param mixed               $source
     * @param ReflectionProperty  $property
     * @param ConvertOptions|null $options
     * @return ArrGetter
     */
    protected function getArrGetter(mixed $source, ReflectionProperty $property, ?ConvertOptions $options = null): GetterInterface
    {
        return (new ArrGetter($source, $property, $options));
    }

    /**
     * @param Arr|DataTransferObject $source
     * @param ReflectionProperty     $property
     * @param ConvertOptions|null    $options
     * @return ArrSetter
     */
    protected function getArrSetter(mixed $source, ReflectionProperty $property, ?ConvertOptions $options = null): SetterInterface
    {
        return new ArrSetter($source, $property, $options);
    }

    /**
     * @return SerializePipeline
     */
    protected function getSerializePipeline(): Pipeline
    {
        return new SerializePipeline();
    }

    /**
     * @return ParsePipeline
     */
    protected function getParsePipeline(): Pipeline
    {
        return new ParsePipeline();
    }


    public function offsetExists(mixed $offset): bool
    {
        try {
            $property = $this->self->getProperty($offset);
            return true;
        } catch (\ReflectionException) {
            return false;
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->$offset;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->self->getProperty($offset)->setValue($this, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->self->getProperty($offset)->setValue($offset);
    }
}
