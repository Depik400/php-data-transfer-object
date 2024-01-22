<?php

namespace Svyazcom\DataTransferObject\Attributes;
use Attribute;
use Svyazcom\DataTransferObject\Attributes\Interfaces\AttributePropertyParseInterface;
use Svyazcom\DataTransferObject\Attributes\Interfaces\DataTransferObjectAttribute;
use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;
use Svyazcom\DataTransferObject\Pipelines\ParsePipeline;
use Svyazcom\DataTransferObject\Pipelines\SerializePipeline;

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


    public function getPipeline(): AbstractPipeline
    {
        return (new ParsePipeline($this));
    }
}
