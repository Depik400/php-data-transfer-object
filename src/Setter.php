<?php

namespace Svyazcom\DataTransferObject;

use Svyazcom\DataTransferObject\Interfaces\SetterInterface;
use Svyazcom\DataTransferObject\Object\Arr;

class Setter implements SetterInterface
{
    protected DataTransferObject|Arr $dto;

    protected \ReflectionProperty $property;
    public function setDestination(DataTransferObject|Arr $object): static
    {
        $this->dto = $object;
        return $this;
    }

    public function setProperty(\ReflectionProperty $reflectionProperty): static
    {
        $this->property = $reflectionProperty;
        return $this;
    }

    public function set($value)
    {
        if ($this->dto instanceof DataTransferObject) {
            $this->property->setValue($this->dto, $value);
        } else {
            $this->dto[$this->property->getName()] = $value;
        }
    }
}
