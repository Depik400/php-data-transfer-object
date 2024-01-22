<?php

namespace Svyazcom\DataTransferObject\Enums;

enum PhpType: string {
    case Boolean = "boolean";
    case Integer = "integer";
    case Double = "double";
    case String = "string";
    case Array = "array";
    case Object = "object";
    case Resource = "resource";
    case ResourceClosed = "resource (closed)";
    case NULL = "NULL";
    case Unknown = "unknown type";
}
