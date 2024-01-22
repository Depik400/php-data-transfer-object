<?php

namespace Svyazcom\DataTransferObject\Attributes;

use Attribute;
use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;
use Svyazcom\DataTransferObject\Pipelines\SerializeArrayPipeline;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::IS_REPEATABLE)]
class PropertySerializeArray extends PropertySerialize
{
    public function getPipeline(): AbstractPipeline
    {
        return (new SerializeArrayPipeline($this));
    }
}
