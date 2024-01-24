<?php

namespace Paulo\TransformPipes;

use Paulo\Attributes\PropertyIgnoreParse;
use Paulo\Attributes\PropertyIgnoreSerialize;
use Paulo\Pipeline\PipelineResult;

/**
 * @extends AbstractPipe<PropertyIgnoreParse|PropertyIgnoreSerialize>
 */
class PropertyIgnorePipe extends AbstractPipe
{
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        return new PipelineResult(next: false, provideValue: false, dropPipeline: true);
    }
}