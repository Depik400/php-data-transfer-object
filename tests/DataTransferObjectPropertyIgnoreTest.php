<?php

use Paulo\Attributes\PropertyIgnoreParse;
use Paulo\Attributes\PropertyIgnoreSerialize;
use Paulo\Attributes\PropertyInternal;
use Paulo\DataTransferObject;

class DataTransferObjectPropertyIgnoreTest extends \PHPUnit\Framework\TestCase
{
    public function test_ignore_property()
    {
        $item = new class extends DataTransferObject {
            #[PropertyInternal]
            public mixed $property = 5;
        };
        $test = ['property' => 1];
        $item->fill($test);
        $this->assertSame(5, $item->property);
        $this->assertSame(null, $item->toArray()['property'] ?? null);
    }

    public function test_ignore_parse_property()
    {
        $item = new class extends DataTransferObject {
            #[PropertyIgnoreParse]
            public mixed $property = 5;
        };
        $test = ['property' => 1];
        $item->fill($test);
        $this->assertSame(5, $item->property);
        $this->assertSame(5, $item->toArray()['property']);
    }

    public function test_ignore_serialize_property()
    {
        $item = new class extends DataTransferObject {
            #[PropertyIgnoreSerialize]
            public mixed $property = 5;
        };
        $test = ['property' => 1];
        $item->fill($test);
        $this->assertSame(1, $item->property);
        $this->assertSame(null, $item->toArray()['property'] ?? null);
    }
}