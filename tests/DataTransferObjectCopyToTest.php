<?php

use Paulo\Attributes\PropertyMapFrom;
use Paulo\Attributes\PropertyMapTo;
use Paulo\DataTransferObject;
use PHPUnit\Framework\TestCase;

class DataTransferObjectCopyToTest extends TestCase
{
    public function testCopyToObjectTwoSection(): void
    {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('test.worker')]
            public mixed $to;
        };
        $cls->fill($array);
        $object = new stdClass();
        $object->test = new stdClass();
        $cls->copyTo($object);
        $this->assertSame('test value', $object->test->worker);
    }
    public function testCopyToObjectTwoArrSection(): void
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

    public function testCopyToArrTwoSection(): void
    {
        $array = ['to' => 'test value'];
        $cls = new class extends DataTransferObject {
            #[PropertyMapTo('test.worker.0.1.2.3')]
            public mixed $to;
        };
        $cls->fill($array);
        $obj = [];
        $cls->copyTo($obj);
        $this->assertSame('test value', $obj['test']['worker'][0][1][2][3]);
    }
}

