<?php

namespace Paulo\Helpers;

use Paulo\ConvertOptions;
use ReflectionAttribute;

class AttributeHelper
{
    public static function filterReflectionAttributes(array $attributes, ConvertOptions $options) {
        $excludeList = $options->getExlcudeAttributesList();
        return  array_filter($attributes,
            fn(ReflectionAttribute $item) => count(
                    array_filter($excludeList, fn($exlcudeItem) => $item->newInstance() instanceof $exlcudeItem)
                ) === 0
        );
    }
}