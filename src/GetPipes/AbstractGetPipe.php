<?php

namespace Paulo\GetPipes;

use Paulo\Attributes\Abstract\GetTransformable;

/**
 * @template T of GetTransformable
 */
abstract class AbstractGetPipe
{
    /** @param T|null $attribute */
    public function __construct(
        protected ?GetTransformable $attribute
    )
    {
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


    abstract public function execute(mixed $source, string $property,mixed $value): GetPipeResult;
}