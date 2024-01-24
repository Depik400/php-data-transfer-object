<?php

namespace Paulo\Attributes\Abstract;

use Paulo\GetPipes\AbstractGetPipe;

abstract class GetTransformable {
    /**
     * @return AbstractGetPipe<static>
     */
    abstract public function getPipeline(): AbstractGetPipe;
}