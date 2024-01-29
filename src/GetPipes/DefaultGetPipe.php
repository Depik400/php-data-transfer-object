<?php

namespace Paulo\GetPipes;
use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\Helpers\ValueHelper;

/**
 * @extends AbstractGetPipe<\Paulo\Attributes\Abstract\GetTransformable>
 */
class DefaultGetPipe extends AbstractGetPipe
{

    public function execute(mixed $source, string $property, mixed $value): GetPipeResult
    {
        return new GetPipeResult(ValueHelper::get($source, $property));
    }
}