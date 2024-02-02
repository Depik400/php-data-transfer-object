<?php
use Paulo\Attributes\PropertyMapFrom;
use Paulo\DataTransferObject;
use PHPUnit\Framework\TestCase;

class DataTransferObjectMapFromTest extends TestCase
{
    public function testMapArrayFromOneSection(): void
    {
        $array = ['from' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('from')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
    }

    public function testMapArrayFromOneTwoSection(): void
    {
        $array = ['from' => ['test' => 'test value']];
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('from.test')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
    }

    public function testMapArrayFromOneTwoNone(): void
    {
        $array = ['from' => ['test' => 'test value']];
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame(null, $cls->to);
    }

    public function testMapFromStdObjectOneSection() {
        $array = new stdClass();
        $array->from = 'test value';
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('from')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
    }

    public function testMapFromStdObjectTwoSection() {
        $array = new stdClass();
        $array->from = new stdClass();
        $array->from->from = 'test value';
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('from.from')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
    }
}