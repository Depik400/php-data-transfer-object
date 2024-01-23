<?php

namespace Paulo\Attributes;
use Attribute;
use Paulo\Attributes\Interfaces\AttributePropertyParseInterface;
use Paulo\Attributes\Interfaces\TransformObjectAttribute;
use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\ParsePipe;
use Paulo\TransformPipes\SerializePipe;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyParse implements AttributePropertyParseInterface, TransformObjectAttribute
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
