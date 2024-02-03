<?php

use Paulo\Attributes\PropertyIgnoreParse;
use Paulo\Attributes\PropertyIgnoreSerialize;
use Paulo\Attributes\PropertyInternal;
use Paulo\Attributes\PropertyMapFrom;
use Paulo\Attributes\PropertyMapTo;
use Paulo\Attributes\PropertyParse;
use Paulo\Attributes\PropertyParseArray;
use Paulo\Attributes\PropertySerialize;
use Paulo\Attributes\PropertySerializeArray;
use Paulo\DataTransferObject;

class InternalClass2 extends DataTransferObject
{
    public mixed $test;


    public static function serialize($item)
    {
        return is_object($item) ? $item->test : $item['test'];
    }
}

class DataTransferObjectSerializeTest extends \PHPUnit\Framework\TestCase
{
    public function test_serialize_dto()
    {
        $item = new class extends DataTransferObject {
            #[PropertySerialize(InternalClass2::class, 'serialize', true, 'array')]
            public InternalClass2 $property;
        };
        $test = ['property' => ['test' => 5]];
        $item->fill($test);
        $this->assertSame(5, $item->property->test);
        $this->assertSame(5, $item->toArray()['property'] ?? null);
    }

    public function test_serialize_dto_array()
    {
        $item = new class extends DataTransferObject {
            #[PropertyMapFrom('from')]
            #[PropertyMapTo('to')]
            #[PropertySerializeArray(InternalClass2::class, 'serialize', true, InternalClass2::class)]
            #[PropertyParseArray(InternalClass2::class, 'wrap', true)]
            public array $property;
        };
        $test = ['from' => [['test' => 5]]];
        $item->fill($test);
        $this->assertSame(5, $item->property[0]->test);
        $this->assertSame(5, $item->toArray()['to'][0] ?? null);
    }

    public function test_serialize_datetime()
    {
        $item = new class extends DataTransferObject {
            #[PropertyMapFrom('from')]
            #[PropertyMapTo('to')]
            #[PropertySerialize(self::class, 'toDateTime', true, DateTime::class)]
            #[PropertyParse(DateTime::class)]
            public DateTime $property;

            public static function toDateTime($i)
            {
                return $i->format('Ymd\THis');
            }
        };
        $test = ['from' => '20230701T100000'];
        $item->fill($test);
        $this->assertSame('20230701T100000', $item->property->format('Ymd\THis'));
        $this->assertSame('20230701T100000', $item->toArray()['to']);
    }
}