<?php
namespace Paulo\Pipelines;

use Paulo\DataTransferObject;
use Paulo\Pipeline\PipelineResult;
use ReflectionClass;

/**
 * @extends AbstractPipe<null>
 */
class DefaultSerializePipe extends AbstractPipe
{

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        $type = $property->getType();
        $item = $value;
       // $item = $property->getValue($source);
        if($type instanceof \ReflectionNamedType && class_exists($type->getName())  && $item) {
            /** @var class-string<DataTransferObject> $className */
            $className = $type->getName();
            $reflectionClass = new ReflectionClass($className);
            if($reflectionClass->isSubclassOf(DataTransferObject::class)) {
                $item = $item->toArray();
            }
        }
        return (new PipelineResult())->setResult($item)->setNext(true);
    }
}