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
    public function setExlcudeAttributesList(array $exlcudeAttributesList): ConvertOptions
    {
        $this->exlcudeAttributesList = $exlcudeAttributesList;
        return $this;
    }

    public function concatWith(ConvertOptions $options) {

    }
}