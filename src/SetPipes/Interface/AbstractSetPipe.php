<?php

namespace Paulo\SetPipes\Interface;

use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\SetPipes\SetPipeResult;

/**
 * @template T of SetTransformable
 */
abstract class AbstractSetPipe
{
    /**
     * @param T|null $attribute
     */
    public function __construct(
        protected ?SetTransformable $attribute
    )
    {
    }

    /**
     * @param \ArrayAccess<string,mixed> $source
     * @param string $property
     * @param mixed $value
     * @return SetPipeResult
     */
    abstract public function execute(\ArrayAccess $source, string $property,mixed $value): SetPipeResult;
}