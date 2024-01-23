<?php

namespace Paulo\Pipeline;

use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\DefaultSerializePipe;

/**
 * @extends Pipeline<ObjectGetter,ArrSetter>
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