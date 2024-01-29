<?php

namespace Paulo\GetPipes;
use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\Attributes\PropertyMapFrom;
use Paulo\Helpers\ValueHelper;

/**
 * @extends AbstractGetPipe<PropertyMapFrom>
 */
class MapFromGetPipe extends AbstractGetPipe
{

    public function execute(mixed $source, string $property, mixed $value): GetPipeResult
    {
        return new GetPipeResult(ValueHelper::get($source, $this->attribute->getMapFrom()));
    }
}