<?php
declare(strict_types=1);

namespace Paulo\Attributes;

use Paulo\Attributes\Interfaces\AttributePropertyBoth;
use Paulo\Attributes\Interfaces\AttributePropertySerializeInterface;
use Paulo\Attributes\Interfaces\TransformObjectAttribute;
use Paulo\TransformPipes\AbstractPipe;
use Paulo\TransformPipes\PropertyIgnorePipe;

#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_CLASS_CONSTANT)]
class PropertyIgnoreSerialize implements AttributePropertySerializeInterface, TransformObjectAttribute
{
    public function getPipeline(): AbstractPipe
    {
        return new PropertyIgnorePipe($this);
    }
}
