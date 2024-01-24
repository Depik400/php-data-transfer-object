<?php

namespace Paulo\Attributes;
use Attribute;
use Paulo\Attributes\Abstract\GetTransformable;
use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\GetPipes\MapFromGetPipe;
use Paulo\SetPipes\MapToSetPipe;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyMapFrom extends GetTransformable {
    public function __construct(
        protected string $mapFrom
    )
    {
    }

    public function getMapFrom(): string
    {
        return $this->mapFrom;
    }

    /**
     * @phpstan-return MapFromGetPipe
     */
    public function getPipeline(): MapFromGetPipe
    {
        return new MapFromGetPipe($this);
    }
}