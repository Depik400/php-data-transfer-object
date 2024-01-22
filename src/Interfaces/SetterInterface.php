<?php

namespace Svyazcom\DataTransferObject\Interfaces;

use ReflectionProperty;
use Svyazcom\DataTransferObject\DataTransferObject;
use Svyazcom\DataTransferObject\Object\Arr;

interface SetterInterface {
    public function setDestination(DataTransferObject|Arr $object): static;

    public function setProperty(ReflectionProperty $reflectionProperty): static;

    public function set($value);
}