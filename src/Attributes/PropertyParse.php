<?php

namespace Paulo\Attributes;
use Attribute;
use Paulo\Attributes\Interfaces\AttributePropertyParseInterface;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\ParsePipe;
use Paulo\Pipelines\SerializePipe;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyParse implements AttributePropertyParseInterface, DataTransferObjectAttribute
{
    public function __construct(
        private readonly string $class,
        private readonly ?string $method = null,
        private readonly bool $static = false,
        private readonly bool $applyNull = true,
    ) {
    }

    public function isApplyNull(): bool
    {
        return $this->applyNull;
    }

    public function isStatic(): bool
    {
        return $this->static;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }


    public function getPipeline(): AbstractPipe
    {
        return (new ParsePipe($this));
    }
}
