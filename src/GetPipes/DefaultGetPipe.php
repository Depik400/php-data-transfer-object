<?php

namespace Paulo\GetPipes;
use Paulo\Attributes\Interfaces\AttributePropertyGetterInterface;

/**
 * @extends AbstractGetPipe<AttributePropertyGetterInterface>
 */
class DefaultGetPipe extends AbstractGetPipe
{

    public function execute(mixed $source, string $property, mixed $value): GetPipeResult
    {
        return new GetPipeResult($this->fetchByDotNotation($source, $property));
    }

    protected function fetchByDotNotation(mixed $source, string $property): mixed
    {
        $sections = explode('.', $property);
        $value = $source;
        foreach ($sections as $section) {
            if(is_null($value)) {
                break;
            }
            if (is_array($value)) {
                $value = $value[$section] ?? null;
            } else if (is_object($value)) {
                $value = $value->$section ?? null;
            } else {
                $value = null;
                break;
            }
        }
        return $value;
    }
}