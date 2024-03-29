<?php
declare(strict_types=1);

namespace Paulo\Attributes;

use Paulo\Attributes\Abstract\Transformable;
use Paulo\Attributes\Interfaces\AttributePropertyBoth;
use Paulo\Attributes\Interfaces\AttributePropertyParseInterface;
use Paulo\TransformPipes\PropertyIgnorePipe;

#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_CLASS_CONSTANT)]
class PropertyInternal extends Transformable implements AttributePropertyBoth
{
    /**
     * @return PropertyIgnorePipe
     */
    public function getPipeline(): PropertyIgnorePipe
    {
        return new PropertyIgnorePipe($this);
    }
}
