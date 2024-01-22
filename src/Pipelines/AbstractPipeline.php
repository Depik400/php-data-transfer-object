<?php

namespace Svyazcom\DataTransferObject\Pipelines;

use Svyazcom\DataTransferObject\PipelineResult;

/**
 * @template T
 */
abstract class AbstractPipeline
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
     * @param mixed|null          $value
     * @return PipelineResult
     */
    abstract public function execute(\ReflectionProperty $property, $value = null): PipelineResult;
}