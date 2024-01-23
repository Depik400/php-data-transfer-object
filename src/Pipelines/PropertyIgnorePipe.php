<?php

namespace Paulo\Pipelines;

use Paulo\PipelineResult;

/**
 * @template T
 */
class PropertyIgnorePipe extends AbstractPipe
{
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        return new PipelineResult(next: false, provideValue: false, dropPipeline: true);

    }
}