<?php

namespace Paulo\SetPipes;

use Paulo\Attributes\PropertyMapTo;
use Paulo\SetPipes\Interface\AbstractSetPipe;

/**
 * @extends AbstractSetPipe<PropertyMapTo>
 */
class MapToAbstractSetPipe extends AbstractSetPipe
{
    public function execute(\ArrayAccess $source, string $property, mixed $value): SetPipeResult
    {
        //FIXME need refactoring
        $sections = explode('.', $this->attribute->getMapTo());
        $first = array_shift($sections);
        if (!isset($source[$first])) {
            $workerItem = [];
        } else {
            $workerItem = $source[$first];
        }
        $last = array_pop($sections);
        $pointer = &$workerItem;
        foreach ($sections as $section) {
            if (!isset($pointer[$section])) {
                $pointer[$section] = [];
            }
            $pointer = &$pointer[$section];
        }
        $pointer[$last] = $value;
        $source[$first] = $workerItem;
        return new SetPipeResult(false);
    }
}