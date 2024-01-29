<?php

namespace Paulo\Object;
/**
 * @implements \ArrayAccess<string,mixed>
 */
class Arr implements \ArrayAccess
{
    /**
     * @param array<string,mixed> $arr
     */
    public function __construct(
        protected array $arr = []
    )
    {
    }

    public function wrapRef(array &$arr): static {
        $this->arr = &$arr;
        return $this;
    }
    /**
     * @return array<string,mixed>
     */
    public function getArray(): array
    {
        return $this->arr;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->arr);
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

    public function __get(string $name): mixed
    {
        return $this->offsetGet($name);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->offsetSet($name, $value);
    }
}
