<?php

namespace Paulo\SetPipes\Interface;

use Paulo\Attributes\Interfaces\AttributePropertySetterInterface;
use Paulo\SetPipes\SetPipeResult;

/**
 * @template T of AttributePropertySetterInterface
 */
abstract class AbstractSetPipe
{
    /**
     * @param T|null $attribute
     */
    public function __construct(
        protected ?AttributePropertySetterInterface $attribute
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