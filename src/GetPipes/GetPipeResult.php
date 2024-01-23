<?php

namespace Paulo\GetPipes;

class GetPipeResult
{
    public function __construct(
        protected mixed $value = null,
        protected bool $next = true,
    )
    {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): GetPipeResult
    {
        $this->value = $value;
        return $this;
    }

    public function isNext(): bool
    {
        return $this->next;
    }

    public function setNext(bool $next): GetPipeResult
    {
        $this->next = $next;
        return $this;
    }
}