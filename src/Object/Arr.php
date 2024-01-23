<?php

namespace Paulo\Object;

class Arr implements \ArrayAccess
{
    public function __construct(
        protected array $arr = []
    )
    {
    }

    public function getArray(): array {
        return $this->arr;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset,$this->arr);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->arr[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->arr[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->arr[$offset]);
    }
}
