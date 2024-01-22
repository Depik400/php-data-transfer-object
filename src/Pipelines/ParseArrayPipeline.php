<?php
declare(strict_types=1);

namespace Svyazcom\DataTransferObject\Pipelines;

use ReflectionProperty;
use Svyazcom\DataTransferObject\Attributes\PropertyParseArray;
use Svyazcom\DataTransferObject\PipelineResult;

/**
 * @extends AbstractPipeline<PropertyParseArray>
 */
class ParseArrayPipeline extends ParsePipeline
{
    /**
     * @param ReflectionProperty  $property
     * @param array<string,mixed> $source
     * @param array<string,mixed> $value
     * @return PipelineResult
     */
    public function execute(ReflectionProperty $property, $value = null): PipelineResult
    {
        $generated = [];
        foreach ($value as $item) {
            $generated[] = $this->create($item);
        }
        return (new PipelineResult())->setResult($generated)->setNext(true);
    }
}