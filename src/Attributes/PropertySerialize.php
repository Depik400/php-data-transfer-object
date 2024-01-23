<?php

namespace Paulo\Attributes;

use Paulo\Enums\PhpType;
use Attribute;
use Paulo\Attributes\Interfaces\AttributePropertySerializeInterface;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\SerializePipe;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class PropertySerialize implements AttributePropertySerializeInterface, DataTransferObjectAttribute
{
    /**
     * @template T
     * @param class-string      $casterClassName
     * @param string            $method
     * @param boolean           $static
     * @param PhpType|class-string<T>|null $fromType
     */
    public function __construct(
        private readonly string $casterClassName,
        private readonly ?string $method = null,
        private readonly bool $static = false,
        private readonly null|string|PhpType $fromType = null,
    ) {
    }

    /** @return AbstractPipe<static> */
    public function getPipeline(): AbstractPipe
    {
        return new SerializePipe($this);
    }

    public function getCasterClassName(): string
    {
        return $this->casterClassName;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function isStatic(): bool
    {
        return $this->static;
    }

    public function getFromType(): PhpType|string|null
    {
        return $this->fromType;
    }
}