<?php
declare(strict_types=1);

namespace Svyazcom\DataTransferObject\Pipelines;

use Exception;
use Svyazcom\DataTransferObject\Attributes\PropertySerializeArray;
use Svyazcom\DataTransferObject\Enums\PhpType;
use Svyazcom\DataTransferObject\Interfaces\GetterInterface;
use Svyazcom\DataTransferObject\PipelineResult;

/** @extends AbstractPipeline<PropertySerializeArray> */
class SerializePipeline extends AbstractPipeline
{

    /**
     * @inheritDoc
     */
    public function execute(\ReflectionProperty $property, $value = null): PipelineResult
    {
        return (new PipelineResult())->setNext($this->cast($value))->setNext(true);
    }

    protected function canCast($value): bool
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
    protected function cast($value)
    {
        if (!$this->canCast($value)) {
            return false;
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