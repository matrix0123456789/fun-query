<?php


use MKrawczyk\FunQuery\FunQuery;
use PHPUnit\Framework\TestCase;

class PipelineNodeTest extends TestCase
{
    public function testSample()
    {
        $data = [
            (object)['kind' => 'dog', 'legs' => 4],
            (object)['kind' => 'cat', 'legs' => 4],
            (object)['kind' => 'centipede', 'legs' => 100],
            (object)['kind' => 'chicken', 'legs' => 2],
        ];
        $wanted = ['cat', 'dog'];

        $fourLegsAlphabetically = FunQuery::create($data)->filter(fn($x) => $x->legs === 4)->sort(fn($x) => $x->kind)->map(fn($x) => $x->kind)->toArray();
        $this->assertEqualsCanonicalizing($wanted, $fourLegsAlphabetically);
    }

    public function testJson()
    {
        $original = ['string', 999, false, true, null];
        $obj = FunQuery::create($original)->filter(fn() => true);
        $originalJson = json_encode($original);
        $resultJson = json_encode($obj);
        $this->assertEquals($originalJson, $resultJson);
    }

    public function testSomeTrue()
    {
        $original = [2, 4, 6, 8];
        $result = FunQuery::create($original)->some(fn($x) => $x == 4);
        $this->assertTrue($result);
    }

    public function testSomeFalse()
    {
        $original = [2, 4, 6, 8];
        $result = FunQuery::create($original)->some(fn($x) => $x == 3);
        $this->assertFalse($result);
    }
    public function testSomeEmpty()
    {
        $original = [];
        $result = FunQuery::create($original)->some();
        $this->assertFalse($result);
    }
    public function testSomeNoEmpty()
    {
        $original = [1,1,1,1];
        $result = FunQuery::create($original)->some();
        $this->assertTrue($result);
    }
}
