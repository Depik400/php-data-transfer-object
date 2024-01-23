<?php

namespace Paulo\Pipeline;

use Paulo\Attributes\Interfaces\AttributePropertyGetterInterface;
use Paulo\GetPipes\AbstractGetPipe;
use Paulo\GetPipes\DefaultGetPipe;
use ReflectionAttribute;

class GetPipeline
{
    public function __construct(
        protected mixed  $getter,
        protected string $property,
    )
    {
    }

    /**
     * @param ReflectionAttribute<AttributePropertyGetterInterface>[] $attributes
     * @return mixed
     */
    public function getWithAttributes(array $attributes): mixed
    {
        $pipelines = $this->getPipelines($attributes);
        $value = null;
        foreach ($pipelines as $pipeline) {
            $result = $pipeline->execute($this->getter, $this->property, $value);
            $value = $result->getValue();
            if (!$result->isNext()) {
                break;
            }
        }
        return $value;
    }

    /**
     * @param ReflectionAttribute<AttributePropertyGetterInterface>[] $attributes
     * @return AbstractGetPipe<AttributePropertyGetterInterface>[]
     */
    protected function getPipelines(array $attributes): array
    {
        $instances = array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes);
        $pipelines = array_map(fn(AttributePropertyGetterInterface $attribute) => $attribute->getPipeline(), $instances);
        array_unshift($pipelines, new DefaultGetPipe(null));
        return $pipelines;
    }
}