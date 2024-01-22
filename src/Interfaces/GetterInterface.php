<?php

namespace Svyazcom\DataTransferObject\Interfaces;

use ReflectionProperty;
use Svyazcom\DataTransferObject\DataTransferObject;
use Svyazcom\DataTransferObject\Object\Arr;

interface GetterInterface {
    public function setSource(DataTransferObject|Arr|array $object): static;

    public function setProperty(ReflectionProperty $reflectionProperty): static;

    public function get();
}