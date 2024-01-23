<?php

namespace Paulo\Attributes;

use Attribute;
use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\SerializeArrayPipe;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::IS_REPEATABLE)]
class PropertySerializeArray extends PropertySerialize
{
    public function getPipeline(): AbstractPipe
    {
        return (new SerializeArrayPipe($this));
    }
}
