<?php

use Paulo\Attributes\PropertyMapFrom;
use Paulo\Attributes\PropertyMapTo;
use Paulo\DataTransferObject;
use PHPUnit\Framework\TestCase;

class DataTransferObjectMapToTest extends TestCase
{
    public function testMapToArrayOneSection(): void
    {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('from')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->to);
        $this->assertSame('test value', $cls->fill($array)->toArray()['from']);
    }

    public function testMapToArrayOneTwoSection(): void
    {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('from.test')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame('test value', $cls->fill($array)->toArray()['from']['test']);
    }

    public function testMapToArrayOneTwoNone(): void
    {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('')]
            public mixed $to;
        };
        $cls->fill($array);
        $this->assertSame(null, $cls->fill($array)->toArray()['to'] ?? null);
    }

    public function testMapToObjectTwoSection(): void
    {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('test.worker.0')]
            public mixed $to;
        };
        $cls->fill($array);
        $object = new stdClass();
        $object->test = new stdClass();
        $cls->copyTo($object);
        $this->assertSame('test value', $object->test->worker[0]);
    }
    public function testMapToObjectTwoArrSection(): void
    {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('test.worker.0')]
            public mixed $to;
        };
        $cls->fill($array);
        $object = new stdClass();
        $object->test = ['worker' => 1];
        $cls->copyTo($object);
        $this->assertSame('test value', $object->test['worker'][0]);
    }
}

