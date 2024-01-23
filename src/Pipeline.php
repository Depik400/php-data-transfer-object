<?php

namespace Paulo;

use ReflectionAttribute;
use ReflectionProperty;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\DefaultParsePipe;

/**
 * @template TGetter of GetterInterface
 * @template TSetter of SetterInterface
 */
abstract class Pipeline
{
    protected ReflectionProperty $property;
    /**
     * @var TGetter
     */
    protected GetterInterface $getter;
    /**
     * @var TSetter
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
     * @param ReflectionAttribute<DataTransferObjectAttribute>[] $attributes
     */
    public function pipeAttributes(array $attributes): void
    {
        $pipelines = $this->getPipelines($attributes);
        $pipedItem = $this->getter->get();
        foreach ($pipelines as $pipeline) {
            $result = $this->execute($pipeline, $pipedItem);
            if($result->isPipeDropped()) {
                echo 'here' . static::class . PHP_EOL;
                return;
            }
            if ($result->canSetValue()) {
                $pipedItem = $result->getResult();
            }
            if (!$result->getNext()) {
                break;
            }
        }
        echo $this->property->getName() . PHP_EOL;
        $this->setter->set($pipedItem);
    }

    /**
     * @template TAttribute
     * @param AbstractPipe<TAttribute> $pipeline
     * @param mixed                    $pipedItem
     */
    abstract protected function execute(AbstractPipe $pipeline, mixed $pipedItem): PipelineResult;

    /**
     * @param ReflectionAttribute<DataTransferObjectAttribute>[] $attributes
     * @return array|AbstractPipe<mixed>[]
     */
    public function getPipelines(array $attributes): array
    {
        $instances = array_map(fn(ReflectionAttribute $attr) => $attr->newInstance(), $attributes);
        return array_map(fn(DataTransferObjectAttribute $attr) => $attr->getPipeline(), $instances);
    }
}