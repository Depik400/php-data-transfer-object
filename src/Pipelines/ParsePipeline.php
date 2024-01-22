<?php
declare(strict_types=1);

namespace Svyazcom\DataTransferObject\Pipelines;

use Svyazcom\DataTransferObject\Attributes\PropertyParse;
use Svyazcom\DataTransferObject\PipelineResult;

/**
 * @extends AbstractPipeline<PropertyParse>
 */
class ParsePipeline extends AbstractPipeline
{

    /**
     * @inheritDoc
     */
    public function execute(\ReflectionProperty $property, $value = null): PipelineResult
    {
        return (new PipelineResult())->setResult($this->create($source[$property->name] ?? null))->setNext(true);
    }

    protected function create($value)
    {
        $class = $this->attribute->getClass();
        $applyNull = $this->attribute->isApplyNull();
        $method = $this->attribute->getMethod();
        $static = $this->attribute->isStatic();
        if ($value instanceof $class) {
            return $value;
        }
        if (!$applyNull && !$value) {
            return null;
        }
        if (is_null($method)) {
            return new ($class)($value);
        } else {
            return $static ?
                ($class)::{$method}($value) : (new ($class)())->{$method}($value);
        }
    }
}