<?php

namespace Paulo\Attributes\Interfaces;

use Paulo\SetPipes\Interface\AbstractSetPipe;

interface AttributePropertySetterInterface {
    /**
     * @return AbstractSetPipe<static>
     */
    public function getPipeline(): AbstractSetPipe;
}