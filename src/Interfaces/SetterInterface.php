<?php

namespace Paulo\Interfaces;

use ReflectionProperty;
use Paulo\DataTransferObject;
use Paulo\Object\Arr;

/**
 * @template T
 */
interface SetterInterface {
    /**
     * @param T $object
     * @return $this
     */
    public function setDestination($object): static;

    public function setProperty(ReflectionProperty $reflectionProperty): static;

    public function set(mixed$value): void;
}