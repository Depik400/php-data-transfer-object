<?php

namespace Svyazcom\DataTransferObject;

use ReflectionAttribute;
use ReflectionProperty;
use Svyazcom\DataTransferObject\Attributes\Interfaces\DataTransferObjectAttribute;
use Svyazcom\DataTransferObject\Interfaces\GetterInterface;
use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;
use Svyazcom\DataTransferObject\Pipelines\DefaultParsePipeline;
use Svyazcom\DataTransferObject\Pipelines\DefaultSerializePipeline;

class SerializePipeline extends Pipeline
{
    protected function execute(AbstractPipeline $pipeline, $pipedItem): PipelineResult
    {
        return $pipeline->execute($this->property, $pipedItem);
    }

    public function getPipelines(array $attributes)
    {
        $pipelines = parent::getPipelines($attributes);
        array_unshift($pipelines, new DefaultSerializePipeline(null));
        return $pipelines;
    }
}