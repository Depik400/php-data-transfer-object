<?php

namespace Paulo\Attributes;
use Attribute;
use Paulo\Attributes\Interfaces\AttributePropertySetterInterface;
use Paulo\SetPipes\Interface\AbstractSetPipe;
use Paulo\SetPipes\MapToAbstractSetPipe;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PropertyMapTo implements AttributePropertySetterInterface {
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
     * @return AbstractSetPipe<static>
     */
    public function getPipeline(): AbstractSetPipe
    {
        return new MapToAbstractSetPipe($this);
    }
}