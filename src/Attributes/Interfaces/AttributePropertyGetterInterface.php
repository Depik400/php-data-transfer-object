<?php

namespace Paulo\Attributes\Interfaces;

use Paulo\GetPipes\AbstractGetPipe;

interface AttributePropertyGetterInterface {
    /** @return AbstractGetPipe<static> */
    public function getPipeline(): AbstractGetPipe;
}