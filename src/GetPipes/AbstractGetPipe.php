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

    abstract public function execute(mixed $source, string $property,mixed $value): GetPipeResult;
}