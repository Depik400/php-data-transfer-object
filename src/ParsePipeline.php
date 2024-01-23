<?php

namespace Paulo;

use ReflectionAttribute;
use ReflectionProperty;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\DefaultParsePipe;
use Paulo\Pipelines\ParsePipe as PipelinesParsePipeline;

class ParsePipeline extends Pipeline
{
    protected function execute(AbstractPipe $pipeline, $pipedItem): PipelineResult
    {
        return $pipeline->execute($this->property, $pipedItem);
    }

    public function getPipelines(array $attributes)
    {
        $pipelines = parent::getPipelines($attributes);
        array_unshift($pipelines, new DefaultParsePipe(null));
        return $pipelines;
    }
}