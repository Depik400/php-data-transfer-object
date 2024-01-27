<?php

namespace Paulo\Pipeline;

use Paulo\Interfaces\GetterInterface;
use Paulo\Interfaces\SetterInterface;
use Paulo\Transform\ArrGetter;
use Paulo\Transform\ObjectSetter;
use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\DefaultParsePipe;

/**
 * @extends Pipeline<GetterInterface,SetterInterface>
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