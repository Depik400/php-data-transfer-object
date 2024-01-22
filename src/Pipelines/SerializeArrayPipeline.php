<?php
declare(strict_types=1);

namespace Svyazcom\DataTransferObject\Pipelines;

use Svyazcom\DataTransferObject\PipelineResult;

class SerializeArrayPipeline extends SerializePipeline
{

    /**
     * @inheritDoc
     */
    public function execute(\ReflectionProperty $property, $value = null): PipelineResult
    {
        $generated = [];
        foreach ($value as $values) {
            $generated[] = $this->cast($values);
        }
        return (new PipelineResult())->setResult($generated)->setNext(true);
    }
}