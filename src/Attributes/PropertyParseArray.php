<?php

namespace Paulo\Attributes;

use Attribute;
use Paulo\TransformPipes\ParseArrayPipe;

#[Attribute(Attribute::TARGET_PROPERTY)]

class PropertyParseArray extends PropertyParse
{
    public function getPipeline(): ParseArrayPipe
    {
        return (new ParseArrayPipe($this));
    }
}
