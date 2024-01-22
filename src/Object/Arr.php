<?php

namespace Svyazcom\DataTransferObject\Object;

class Arr extends \ArrayObject
{
    public function __construct($array = array(), $flags = 2)
    {
        // let’s give the objects the right and not the inherited name
        $class = get_class($this);

        foreach ($array as $offset => $value)
            $this->offsetSet($offset, is_array($value) ? new $class($value) : $value);

        $this->setFlags($flags);
    }

    public function getArray()
    {
        return array_map(function ($item) {
            return is_object($item) ? $item->getArray() : $item;
        }, $this->getArrayCopy());
    }
}
