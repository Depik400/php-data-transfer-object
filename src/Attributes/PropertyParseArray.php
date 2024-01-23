<?php

namespace Paulo\Attributes;

use Attribute;
use Paulo\Attributes\Interfaces\TransformObjectAttribute;
use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\ParseArrayPipe;

#[Attribute(Attribute::TARGET_PROPERTY)]

class PropertyParseArray extends PropertyParse implements TransformObjectAttribute
{
    public function getPipeline(): AbstractPipe
    {
        return (new ParseArrayPipe($this));
    }
}
