<?php

namespace Svyazcom\DataTransferObject\Attributes;

use Attribute;
use Svyazcom\DataTransferObject\Attributes\Interfaces\DataTransferObjectAttribute;
use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;
use Svyazcom\DataTransferObject\Pipelines\ParseArrayPipeline;

#[Attribute(Attribute::TARGET_PROPERTY)]

class PropertyParseArray extends PropertyParse implements DataTransferObjectAttribute
{
    public function getPipeline(): AbstractPipeline
    {
        return (new ParseArrayPipeline($this));
    }
}
