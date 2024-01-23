<?php
declare(strict_types=1);

namespace Paulo\Pipelines;

use Paulo\PipelineResult;

class SerializeArrayPipe extends SerializePipe
{

    /**
     * @inheritDoc
     */
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        $generated = [];
        $result = (new PipelineResult())->setNext(true);
        if(is_null($value)) {
            return $result->setResult($value);
        }
        foreach ($value as $values) {
            $generated[] = $this->cast($values);
        }
        return $result->setResult($generated);
    }
}