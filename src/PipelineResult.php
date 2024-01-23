<?php

namespace Paulo;

class PipelineResult
{
    public function __construct(
        protected mixed $result = null,
        protected bool  $next = true,
        protected bool  $provideValue = true,
        protected bool  $dropPipeline = false,
    )
    {
    }

    public function setProvideValue(bool $provideValue): void
    {
        $this->provideValue = $provideValue;
    }

    public function setDropPipeline(bool $dropPipeline): void
    {
        $this->dropPipeline = $dropPipeline;
    }

    public function setNext(bool $next): static
    {
        $this->next = $next;
        return $this;
    }

    public function setResult(mixed $result): static
    {
        $this->result = $result;
        return $this;
    }

    public function getNext(): bool
    {
        return $this->next;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function canSetValue(): bool
    {
        return $this->provideValue;
    }

    public function isPipeDropped(): bool {
        return $this->dropPipeline;
    }
}