<?php

namespace Paulo\Reflect;


use ReflectionProperty;

class Reflect
{
    public static function getPropertyByName(object $dto, string $property): ?ReflectionProperty
    {
        $class = new \ReflectionClass($dto);
        if (!$class->hasProperty($property)) {
            return null;
        }
        return $class->getProperty($property);
    }
}