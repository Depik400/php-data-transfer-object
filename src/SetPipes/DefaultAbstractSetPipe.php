<?php

namespace Paulo\SetPipes;

use Paulo\Attributes\Abstract\SetTransformable;
use Paulo\SetPipes\Interface\AbstractSetPipe;

/**
 * @extends AbstractSetPipe<SetTransformable>
 */
class DefaultAbstractSetPipe extends AbstractSetPipe
{
    public function execute(\ArrayAccess $source, string $property,mixed $value): SetPipeResult {
        $sections = explode('.', $property);
        $last = array_pop($sections);
        foreach ($sections as $section) {
            if(!isset($source->$section)) {
                $source[$section] = [];
            }
        }
        $source[$last] = $value;
        return new SetPipeResult(false);
    }
}