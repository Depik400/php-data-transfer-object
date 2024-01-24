<?php

namespace Paulo\Attributes;

use Attribute;
use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\SerializeArrayPipe;
use Paulo\TransformPipes\SerializePipe;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::IS_REPEATABLE)]
class PropertySerializeArray extends PropertySerialize
{
    public function getPipeline(): SerializePipe
    {
        return (new SerializeArrayPipe($this));
    }
}
