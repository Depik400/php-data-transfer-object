<?php

namespace Paulo\Pipeline;

use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Transform\ArrSetter;
use Paulo\Transform\ObjectGetter;
use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\DefaultSerializePipe;

/**
 * @extends Pipeline<GetterInterface<object>,SetterInterface<object>>
 */
class SerializePipeline extends Pipeline
{
    protected function execute(AbstractPipe $pipeline, $pipedItem): PipelineResult
    {
        return $pipeline->execute($this->property, $pipedItem);
    }

    /**
     * @inheritDoc
     */
    public function getPipelines(array $attributes): array
    {
        $pipelines = parent::getPipelines($attributes);
        array_unshift($pipelines, new DefaultSerializePipe(null));
        return $pipelines;
    }
}