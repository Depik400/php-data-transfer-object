<?php
namespace Paulo\Pipelines;

use \ReflectionClass;
use Paulo\DataTransferObject;
use Paulo\PipelineResult;

/**
 * @extends AbstractPipe<null>
 */
class DefaultParsePipe extends AbstractPipe
{

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
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