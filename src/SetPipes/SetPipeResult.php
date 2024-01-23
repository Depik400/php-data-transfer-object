<?php

namespace Paulo\SetPipes;

class SetPipeResult
{
    public function __construct(
        protected bool $next = true
    )
    {
    }

    public function isNext(): bool
    {
        return $this->next;
    }

    public function setNext(bool $next): SetPipeResult
    {
        $this->next = $next;
        return $this;
    }
}