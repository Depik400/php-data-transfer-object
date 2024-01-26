<?php

use Paulo\Attributes\PropertyMapFrom;
use Paulo\Attributes\PropertyMapTo;
use Paulo\DataTransferObject;
use PHPUnit\Framework\TestCase;

class DataTransferObjectMapToTest extends TestCase
{
    public function testMapFromOneSection(): void {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('from')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
        $this->assertSame('test value',$cls->fill($array)->toArray()['from']);
    }

    public function testMapFromOneTwoSection(): void {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('from.test')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value',$cls->fill($array)->toArray()['from']['test']);
    }

    public function testMapFromOneTwoNone(): void {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame(null,$cls->fill($array)->toArray()['to'] ?? null);
    }
}

