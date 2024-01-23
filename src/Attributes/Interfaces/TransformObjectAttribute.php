<?php

namespace Paulo\Attributes\Interfaces;

use Paulo\TransformPipes\AbstractPipe;

interface TransformObjectAttribute {
    /** @return AbstractPipe<static> */
    public function getPipeline(): AbstractPipe;
}