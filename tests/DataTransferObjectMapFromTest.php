<?php
use Paulo\Attributes\PropertyMapFrom;
use Paulo\DataTransferObject;
use PHPUnit\Framework\TestCase;

class DataTransferObjectMapFromTest extends TestCase
{
    public function testMapFromOneSection(): void
    {
        $array = ['from' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('from')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
    }

    public function testMapFromOneTwoSection(): void
    {
        $array = ['from' => ['test' => 'test value']];
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('from.test')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
    }

    public function testMapFromOneTwoNone(): void
    {
        $array = ['from' => ['test' => 'test value']];
        $cls = new class extends DataTransferObject {
            #[PropertyMapFrom('')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame(null, $cls->to);
    }
}