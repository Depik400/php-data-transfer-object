<?php

namespace Paulo;

class ConvertOptions
{
    /**
     * @param class-string[] $exlcudeAttributesList
     */
    public function __construct(
        protected array $exlcudeAttributesList = [],
    )
    {
    }

    /**
     * @return class-string[]
     */
    public function getExlcudeAttributesList(): array
    {
        return $this->exlcudeAttributesList;
    }

    /**
     * @param class-string[] $exlcudeAttributesList
     */
    public function setExlcudeAttributesList(array $exlcudeAttributesList): static
    {
        $this->exlcudeAttributesList = $exlcudeAttributesList;
        return $this;
    }

    public function concatWith(?ConvertOptions $options): static
    {
        if (is_null($options)) {
            return $this;
        }
        $this->exlcudeAttributesList = array_merge($options->getExlcudeAttributesList(), $this->exlcudeAttributesList);
        return $this;
    }
}