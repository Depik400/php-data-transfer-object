<?php

namespace Paulo\Attributes\Abstract;

use Paulo\TransformPipes\AbstractPipe;

abstract class Transformable {
    /**
     * @return AbstractPipe<mixed>
     */
    abstract public function getPipeline(): AbstractPipe;
}