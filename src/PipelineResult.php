<?php

namespace Paulo;

class PipelineResult {
    /** @var mixed $result */
    protected $result;

    protected bool $next;

    public function setNext(bool $next): static {
        $this->next = $next;
        return $this;
    }

    public function setResult(mixed $result): static {
        $this->result = $result;
        return $this;
    }

    public function getNext(): bool {
        return $this->next;
    }

    public function getResult(): mixed {
        return $this->result;
    }
}