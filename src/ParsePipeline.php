<?php

namespace Svyazcom\DataTransferObject;

use ReflectionAttribute;
use ReflectionProperty;
use Svyazcom\DataTransferObject\Attributes\Interfaces\DataTransferObjectAttribute;
use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;
use Svyazcom\DataTransferObject\Pipelines\DefaultParsePipeline;
use Svyazcom\DataTransferObject\Pipelines\ParsePipeline as PipelinesParsePipeline;

class ParsePipeline extends Pipeline
{
    protected function execute(AbstractPipeline $pipeline, $pipedItem): PipelineResult
    {
        return $pipeline->execute($this->property, $pipedItem);
    }

    public function getPipelines(array $attributes)
    {
        $pipelines = parent::getPipelines($attributes);
        array_unshift($pipelines, new DefaultParsePipeline(null));
        return $pipelines;
    }
}