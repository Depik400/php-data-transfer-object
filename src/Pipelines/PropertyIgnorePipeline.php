<?php

namespace Paulo\Pipelines;

use Paulo\PipelineResult;

/**
 * @template T
 */
abstract class PropertyIgnorePipeline
{
    /** @var T $attribute */
    protected $attribute;

    /** @param T $attribute */
    final public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @param \ReflectionProperty $property
     * @param array<string,mixed> $source
     * @param mixed|null          $value
     * @return PipelineResult
     */
    abstract public function execute(\ReflectionProperty $property, $value = null): PipelineResult;
}