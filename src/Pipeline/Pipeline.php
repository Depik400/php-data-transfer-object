<?php

namespace Paulo\Pipeline;

use Paulo\Attributes\Abstract\Transformable;
use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\TransformPipes\AbstractPipe;
use ReflectionAttribute;
use ReflectionProperty;

/**
 * @template TGetter of GetterInterface
 * @template TSetter of SetterInterface
 */
abstract class Pipeline
{
    protected ReflectionProperty $property;
    /**
     * @var TGetter $getter
     */
    protected GetterInterface $getter;
    /**
     * @var TSetter $setter
     */
    protected SetterInterface $setter;

    public function property(ReflectionProperty $reflectionProperty): static
    {
        $this->property = $reflectionProperty;
        return $this;
    }

    /**
     * @param TGetter $source
     * @return $this
     */
    public function source(GetterInterface $source): static
    {
        $this->getter = $source;
        return $this;
    }

    /**
     * @param TSetter $source
     * @return $this
     */
    public function destination(SetterInterface $source): static
    {
        $this->setter = $source;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param ReflectionAttribute<\Paulo\Attributes\Abstract\Transformable>[] $attributes
     */
    public function pipeAttributes(array $attributes): void
    {
        $pipelines = $this->getPipelines($attributes);
        $pipedItem = $this->getter->get();
        foreach ($pipelines as $pipeline) {
            $result = $this->execute($pipeline, $pipedItem);
            if($result->isPipeDropped()) {
                return;
            }
            if ($result->canSetValue()) {
                $pipedItem = $result->getResult();
            }
            if (!$result->getNext()) {
                break;
            }
        }
        $this->setter->set($pipedItem);
    }

    /**
     * @template TAttribute
     * @param AbstractPipe<TAttribute> $pipeline
     * @param mixed                    $pipedItem
     */
    abstract protected function execute(AbstractPipe $pipeline, mixed $pipedItem): PipelineResult;

    /**
     * @param ReflectionAttribute<\Paulo\Attributes\Abstract\Transformable>[] $attributes
     * @return array|AbstractPipe<mixed>[]
     */
    public function getPipelines(array $attributes): array
    {
        $instances = array_map(fn(ReflectionAttribute $attr) => $attr->newInstance(), $attributes);
        return array_map(fn(Transformable $attr) => $attr->getPipeline(), $instances);
    }
}