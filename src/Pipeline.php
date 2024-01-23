<?php

namespace Paulo;

use ReflectionAttribute;
use ReflectionProperty;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\DefaultParsePipe;

abstract class Pipeline
{
    protected ReflectionProperty $property;

    protected GetterInterface $getter;

    protected SetterInterface $setter;

    public function property(ReflectionProperty $reflectionProperty): static
    {
        $this->property = $reflectionProperty;
        return $this;
    }

    
    public function source(GetterInterface $source)
    {
        $this->getter = $source;
        return $this;
    }

    public function destination(SetterInterface $source)
    {
        $this->setter = $source;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param ReflectionAttribute<DataTransferObjectAttribute>[] $attributes
     */
    public function pipeAttributes(array $attributes)
    {
        $pipelines = $this->getPipelines($attributes);
        $pipedItem = $this->getter->get();
        foreach ($pipelines as $pipeline) {
            $result = $this->execute($pipeline, $pipedItem);
            $pipedItem = $result->getResult();
            if (!$result->getNext()) {
                break;
            }
        }

        $this->setter->set($pipedItem);
    }

    abstract protected function execute(AbstractPipe $pipeline, $pipedItem);

    public function getPipelines(array $attributes)
    {
        $instances = array_map(fn(ReflectionAttribute $attr) => $attr->newInstance(), $attributes);
        return array_map(fn(DataTransferObjectAttribute $attr) => $attr->getPipeline(), $instances);
    }
}