<?php

namespace Paulo;

use Paulo\Object\Arr;
use ReflectionAttribute;
use ReflectionProperty;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\DefaultParsePipe;
use Paulo\Pipelines\ParsePipe as PipelinesParsePipeline;

/**
 * @extends Pipeline<Arr,DataTransferObject>
 */
class ParsePipeline extends Pipeline
{
    protected function execute(AbstractPipe $pipeline,mixed $pipedItem): PipelineResult
    {
        return $pipeline->execute($this->property, $pipedItem);
    }

    /**
     * @inheritDoc
     */
    public function getPipelines(array $attributes): array
    {
        $pipelines = parent::getPipelines($attributes);
        array_unshift($pipelines, new DefaultParsePipe(null));
        return $pipelines;
    }
}