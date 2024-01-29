<?php

namespace Paulo\Helpers;

class ValueHelper
{
    public static function get(object|array $value, string $path)
    {
        $sections = explode('.', $path);
        foreach ($sections as $section) {
            if (is_null($value)) {
                break;
            }
            if (is_array($value)) {
                $value = $value[$section] ?? null;
            } else if (is_object($value)) {
                $value = $value->$section ?? null;
            } else {
                $value = null;
                break;
            }
        }
        return $value;
    }

    public static function &getRef(array|object $source, string $path)
    {
        $var = is_array($source) ? ($source[$path] ?? null) : ($source->$path ?? null);
        return $var;
    }

    public static function set(object|array &$source, string $path, mixed $value): void
    {
        $sections = explode('.', $path);
        $sectionsCount = count($sections);
        $first = array_shift($sections);
        if (self::get($source, $path) === null) {
            $workerItem = [];
        } else {
            $workerItem = self::get($source, $first);
        }
        $last = array_pop($sections);
        $pointer = &$workerItem;
        foreach ($sections as $section) {
            if (self::get($workerItem, $section) === null) {
                self::set($source, $section, []);
            }
            $pointer = &self::getRef($source, $section);
        }
        if ($sectionsCount > 1) {
            self::set($pointer,$last, $value);
            if(is_array($source)) {
                $source[$first] = $pointer;
            } else {
                $source->$first = $pointer;
            }
        } else {
            if(is_array($source)) {
                $source[$first] = $value;
            } else {
                $source->$first = $value;
            }
        }
    }
}