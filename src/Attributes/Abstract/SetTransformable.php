<?php

namespace Paulo\Attributes\Abstract;

use Paulo\SetPipes\Interface\AbstractSetPipe;

abstract class SetTransformable {
    /**
     * @return AbstractSetPipe<static>
     */
    abstract public function getPipeline(): AbstractSetPipe;
}