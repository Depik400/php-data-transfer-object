<?php

namespace Svyazcom\DataTransferObject\Attributes;

use Svyazcom\DataTransferObject\Enums\PhpType;
use Attribute;
use Svyazcom\DataTransferObject\Attributes\Interfaces\AttributePropertySerializeInterface;
use Svyazcom\DataTransferObject\Attributes\Interfaces\DataTransferObjectAttribute;
use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;
use Svyazcom\DataTransferObject\Pipelines\SerializePipeline;

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

    /** @return AbstractPipeline<static> */
    public function getPipeline(): AbstractPipeline
    {
        return new SerializePipeline($this);
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
