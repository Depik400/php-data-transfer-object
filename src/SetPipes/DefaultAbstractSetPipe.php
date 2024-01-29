<?php

namespace Paulo\SetPipes;

use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\Helpers\ValueHelper;
use Paulo\SetPipes\Interface\AbstractSetPipe;

/**
 * @extends AbstractSetPipe<SetTransformable>
 */
class DefaultAbstractSetPipe extends AbstractSetPipe
{
    public function execute(mixed $source, string $property,mixed $value): SetPipeResult {
        ValueHelper::set($source, $property, $value);
        return new SetPipeResult(false);
    }
}