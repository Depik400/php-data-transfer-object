<?php

namespace Paulo\Attributes;
use Attribute;
use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\SetPipes\MapToSetPipe;


#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyMapTo extends SetTransformable {
    public function __construct(
        protected string $mapTo
    )
    {
    }

    public function getMapTo(): string
    {
        return $this->mapTo;
    }

    /**
     * @return MapToSetPipe
     */
    public function getPipeline(): MapToSetPipe
    {
        return new MapToSetPipe($this);
    }
}