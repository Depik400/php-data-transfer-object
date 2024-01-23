<?php
declare(strict_types=1);

namespace Paulo\Attributes;

use Paulo\Attributes\Interfaces\AttributePropertyBoth;
use Paulo\Attributes\Interfaces\AttributePropertySerializeInterface;
use Paulo\Attributes\Interfaces\DataTransferObjectAttribute;
use Paulo\Pipelines\AbstractPipe;
use Paulo\Pipelines\PropertyIgnorePipe;

#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_CLASS_CONSTANT)]
class PropertyIgnoreSerialize implements AttributePropertySerializeInterface, DataTransferObjectAttribute
{
    public function getPipeline(): AbstractPipe
    {
        return new PropertyIgnorePipe($this);
    }
}
