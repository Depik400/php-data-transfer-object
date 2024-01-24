<?php

namespace Paulo\GetPipes;
use Paulo\Attributes\Abstract\GetTransformable;

/**
 * @extends AbstractGetPipe<\Paulo\Attributes\Abstract\GetTransformable>
 */
class DefaultGetPipe extends AbstractGetPipe
{

    public function execute(mixed $source, string $property, mixed $value): GetPipeResult
    {
        return new GetPipeResult($this->fetchByDotNotation($source, $property));
    }
}