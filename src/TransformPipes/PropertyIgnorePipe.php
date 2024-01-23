<?php

namespace Paulo\TransformPipes;

use Paulo\Attributes\PropertyIgnore;
use Paulo\Pipeline\PipelineResult;

/**
 * @extends AbstractPipe<PropertyIgnore>
 */
class PropertyIgnorePipe extends AbstractPipe
{
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        return new PipelineResult(next: false, provideValue: false, dropPipeline: true);
    }
}