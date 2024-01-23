<?php

namespace Paulo\Interfaces;

use ReflectionProperty;
use Paulo\DataTransferObject;
use Paulo\Object\Arr;
use Swoole\FastCGI\Record\Data;

/**
 * @template T
 */
interface GetterInterface {
    /**
     * @param T $object
     * @return $this
     */
    public function setSource($object): static;

    public function setProperty(ReflectionProperty $reflectionProperty): static;

    public function get(): mixed;
}