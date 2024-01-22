<?php
namespace Svyazcom\DataTransferObject\Pipelines;

use \ReflectionClass;
use Svyazcom\DataTransferObject\DataTransferObject;
use Svyazcom\DataTransferObject\PipelineResult;

/**
 * @extends AbstractPipeline<null>
 */
class DefaultParsePipeline extends AbstractPipeline
{

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function execute(\ReflectionProperty $property,$value = null): PipelineResult
    {
        $name = $property->getName();
        $type = $property->getType();
        $item = $value;
        if($type instanceof \ReflectionNamedType && class_exists($type->getName())  && $item) {
            /** @var class-string<DataTransferObject> $className */
            $className = $type->getName();
            $reflectionClass = new ReflectionClass($className);
            if($reflectionClass->isSubclassOf(DataTransferObject::class)) {
                $item = $className::wrap($item);
            }
        }
        return (new PipelineResult())->setResult($item)->setNext(true);
    }
}