<?php

namespace Paulo;

use ReflectionAttribute;
use ReflectionProperty;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Interfaces\GetterInterface;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\DefaultParsePipe;
use Paulo\Pipelines\DefaultSerializePipe;

class SerializePipeline extends Pipeline
{
    protected function execute(AbstractPipe $pipeline, $pipedItem): PipelineResult
    {
        return $pipeline->execute($this->property, $pipedItem);
    }

    public function getPipelines(array $attributes)
    {
        $pipelines = parent::getPipelines($attributes);
        array_unshift($pipelines, new DefaultSerializePipe(null));
        return $pipelines;
    }
}