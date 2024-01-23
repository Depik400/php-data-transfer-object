<?php
declare(strict_types=1);

namespace Paulo\Pipelines;

use Paulo\Attributes\PropertyParse;
use Paulo\Pipeline\PipelineResult;

/**
 * @extends AbstractPipe<PropertyParse>
 */
class ParsePipe extends AbstractPipe
{

    /**
     * @inheritDoc
     */
    public function execute(\ReflectionProperty $property, mixed $value = null): PipelineResult
    {
        return (new PipelineResult())->setResult($this->create($value))->setNext(true);
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