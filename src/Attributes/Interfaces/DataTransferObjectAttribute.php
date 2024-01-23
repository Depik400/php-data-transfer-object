<?php

namespace Paulo\Attributes\Interfaces;

use Paulo\Pipelines\AbstractPipe;

interface DataTransferObjectAttribute {
    /** @return AbstractPipe<static> */
    public function getPipeline(): AbstractPipe;
}