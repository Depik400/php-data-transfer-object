<?php

namespace Svyazcom\DataTransferObject;

use ReflectionProperty;
use Svyazcom\DataTransferObject\Interfaces\GetterInterface;
use Svyazcom\DataTransferObject\Object\Arr;

class Getter implements GetterInterface  {
    protected DataTransferObject|Arr|array $dto;

    protected ReflectionProperty $property;

    public function setSource(DataTransferObject|Arr|array $object): static {
        $this->dto = $object;
        return $this;
    }

    
    public function setProperty(ReflectionProperty $reflectionProperty): static {
        $this->property = $reflectionProperty;
        return $this;
    }


    public function get() {
        if($this->dto instanceof DataTransferObject) {
            return $this->property->getValue($this->dto);
        } else {
            return $this->dto[$this->property->getName()];
        }
    }
}