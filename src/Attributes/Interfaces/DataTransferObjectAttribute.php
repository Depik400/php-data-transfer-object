<?php

namespace Paulo\Attributes\Interfaces;

use Paulo\TransformPipes\AbstractPipe;

interface DataTransferObjectAttribute {
    /** @return AbstractPipe<static> */
    public function getPipeline(): AbstractPipe;
}