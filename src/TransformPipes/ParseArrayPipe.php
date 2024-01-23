<?php
declare(strict_types=1);

namespace Paulo\TransformPipes;

use Paulo\Attributes\PropertyParseArray;
use Paulo\Pipeline\PipelineResult;
use ReflectionProperty;

class ParseArrayPipe extends ParsePipe
{
    /**
     * @param ReflectionProperty  $property
     * @param mixed|null          $value
     * @return PipelineResult
     */
    public function execute(ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        $generated = [];
        $result = (new PipelineResult())->setNext(true);
        if(is_null($value)) {
            return $result->setResult(null);
        }
        foreach ($value as $item) {
            $generated[] = $this->create($item);
        }
        return $result->setResult($generated);
    }
}