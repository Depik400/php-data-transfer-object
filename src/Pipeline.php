<?php

namespace Svyazcom\DataTransferObject;

use ReflectionAttribute;
use ReflectionProperty;
use Svyazcom\DataTransferObject\Attributes\Interfaces\DataTransferObjectAttribute;
use Svyazcom\DataTransferObject\Interfaces\GetterInterface;
use Svyazcom\DataTransferObject\Interfaces\SetterInterface;
use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;
use Svyazcom\DataTransferObject\Pipelines\DefaultParsePipeline;

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
     * @return mixed
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

    abstract protected function execute(AbstractPipeline $pipeline, $pipedItem);

    public function getPipelines(array $attributes)
    {
        $instances = array_map(fn(ReflectionAttribute $attr) => $attr->newInstance(), $attributes);
        return array_map(fn(DataTransferObjectAttribute $attr) => $attr->getPipeline(), $instances);
    }
}