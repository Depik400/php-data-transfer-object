<?php

namespace Svyazcom\DataTransferObject\Attributes\Interfaces;

use Svyazcom\DataTransferObject\Pipelines\AbstractPipeline;

interface DataTransferObjectAttribute {
    /** @return AbstractPipeline<static> */
    public function getPipeline(): AbstractPipeline;
}