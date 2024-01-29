<?php

namespace Paulo\Pipeline;

use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\SetPipes\DefaultAbstractSetPipe;
use Paulo\SetPipes\Interface\AbstractSetPipe;
use ReflectionAttribute;

class SetPipeline
{
    /**
     * @param \ArrayAccess<string, mixed> $setter
     * @param string                      $property
     */
    public function __construct(
        protected mixed  $setter,
        protected string $property,
    )
    {
    }

    /**
     * @param mixed                                   $value
     * @param ReflectionAttribute<SetTransformable>[] $attributes
     * @return void
     */
    public function setWithAttributes(mixed $value, array $attributes): void
    {
        $pipelines = $this->getPipelines($attributes);
        foreach ($pipelines as $pipeline) {
            $result = $pipeline->execute($this->setter, $this->property, $value);
            if (!$result->isNext()) {
                break;
            }
        }
    }

    /**
     * @param ReflectionAttribute<SetTransformable>[] $attributes
     * @return AbstractSetPipe<SetTransformable>[]
     */
    protected function getPipelines(array $attributes): array
    {
        $instances = array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $attributes);
        $pipelines = array_map(fn(SetTransformable $attribute) => $attribute->getPipeline(), $instances);
        $pipelines[] = new DefaultAbstractSetPipe(null);
        return $pipelines;
    }
}