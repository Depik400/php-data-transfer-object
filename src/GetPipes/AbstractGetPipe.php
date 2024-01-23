<?php

namespace Paulo\GetPipes;

use Paulo\Attributes\Interfaces\AttributePropertyGetterInterface;

/**
 * @template T of AttributePropertyGetterInterface
 */
abstract class AbstractGetPipe
{
    /** @param T|null $attribute */
    public function __construct(
        protected ?AttributePropertyGetterInterface $attribute
    )
    {
    }

    abstract public function execute(mixed $source, string $property,mixed $value): GetPipeResult;
}