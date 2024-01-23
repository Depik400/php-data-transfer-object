<?php

namespace Paulo\Pipeline;

use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\DefaultParsePipe;

/**
 * @extends Pipeline<ArrGetter,ObjectSetter>
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