<?php

namespace Paulo\Attributes;

use Attribute;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\ParseArrayPipe;

#[Attribute(Attribute::TARGET_PROPERTY)]

class PropertyParseArray extends PropertyParse implements DataTransferObjectAttribute
{
    public function getPipeline(): AbstractPipe
    {
        return (new ParseArrayPipe($this));
    }
}
