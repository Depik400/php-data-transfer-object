<?php
declare(strict_types=1);

namespace Paulo\TransformPipes;

use Exception;
use Paulo\Attributes\PropertySerialize;
use Paulo\Attributes\PropertySerializeArray;
use Paulo\Enums\PhpType;
use Paulo\Pipeline\PipelineResult;

/** @extends AbstractPipe<PropertySerialize> */
class SerializePipe extends AbstractPipe
{

    /**
     * @inheritDoc
     */
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        return (new PipelineResult())->setResult($this->cast($value))->setNext(true);
    }

    protected function canCast(mixed $value): bool
    {
        $fromType = $this->attribute->getFromType();
        if ($fromType === null) {
            return true;
        }
        if ($fromType instanceof PhpType) {
            return $fromType->value === \gettype($value);
        }
        return gettype($value) === PhpType::Object->value && \class_exists($fromType);
    }

    /**
     * @param mixed $value
     * @return false|mixed
     */
    protected function cast(mixed $value)
    {
        if (!$this->canCast($value)) {
            return $value;
        }
        $static = $this->attribute->isStatic();
        $method = $this->attribute->getMethod();
        $casterClassName = $this->attribute->getCasterClassName();
        $fromType = $this->attribute->getFromType();
        if ($static) {
            if (is_null($method)) {
                throw new Exception("Failed to cast value by $casterClassName. method is undefined");
            }
            return ($casterClassName)::{$method}($value);
        } else if($casterClassName === $fromType) {
            return $value->{$method}();
        } else {
            $instance = new ($casterClassName)();
            $method = $method ?? '__invoke';
            $instance->{$method}($value);
            return $instance;
        }
    }
}