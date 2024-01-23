<?php
declare(strict_types=1);

namespace Paulo\Pipelines;

use ReflectionProperty;
use Paulo\Attributes\PropertyParseArray;
use Paulo\PipelineResult;

/**
 * @extends AbstractPipe<PropertyParseArray>
 */
class ParseArrayPipe extends ParsePipe
{
    /**
     * @param ReflectionProperty  $property
     * @param array<string,mixed> $source
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