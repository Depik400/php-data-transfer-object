<?php

use Paulo\Attributes\PropertyIgnoreParse;
use Paulo\Attributes\PropertyIgnoreSerialize;
use Paulo\Attributes\PropertyInternal;
use Paulo\Attributes\PropertyParse;
use Paulo\Attributes\PropertyParseArray;
use Paulo\DataTransferObject;

class InternalClass extends DataTransferObject
{
    public mixed $test;
}
class DataTransferObjectParseTest extends \PHPUnit\Framework\TestCase
{
    public function test_parse_dto()
    {
        $item = new class extends DataTransferObject {
            public InternalClass $property;
        };
        $test = ['property' => ['test' => 5]];
        $item->fill($test);
        $this->assertSame(5, $item->property->test);
        $this->assertSame(5, $item->toArray()['property']['test'] ?? null);
    }
    public function test_parse_dto_array()
    {
        $item = new class extends DataTransferObject {
            #[PropertyParseArray(InternalClass::class, 'wrap', true)]
            public array $property;
        };
        $test = ['property' => [['test' => 5]]];
        $item->fill($test);
        $this->assertSame(5, $item->property[0]->test);
        $this->assertSame(5, $item->toArray()['property'][0]['test'] ?? null);
    }

    public function test_parse_datetime()
    {
        $item = new class extends DataTransferObject {
            #[PropertyParse(DateTime::class)]
            public DateTime $property;
        };
        $test = ['property' => '20230701T150000'];
        $item->fill($test);
        $this->assertSame('20230701T150000', $item->property->format('Ymd\THis'));
    }

    public function test_parse_datetime_array()
    {
        $item = new class extends DataTransferObject {
            #[PropertyParseArray(DateTime::class)]
            public array $property;
        };
        $test = ['property' => ['20230701T150000']];
        $item->fill($test);
        $this->assertSame('20230701T150000', $item->property[0]->format('Ymd\THis'));
    }
}