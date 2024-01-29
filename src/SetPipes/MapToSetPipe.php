<?php

namespace Paulo\SetPipes;

use Paulo\Attributes\PropertyMapTo;
use Paulo\Helpers\ValueHelper;
use Paulo\SetPipes\Interface\AbstractSetPipe;

/**
 * @extends AbstractSetPipe<PropertyMapTo>
 */
class MapToSetPipe extends AbstractSetPipe
{
    public function execute(mixed $source, string $property, mixed $value): SetPipeResult
    {
        ValueHelper::set($source,$this->attribute->getMapTo(), $value);
        return new SetPipeResult(false);
    }
}