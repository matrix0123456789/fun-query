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

        $fourLegsAlphabetically = FunQuery::create($data)->filter(fn($x) => $x->legs === 4)->sort(fn($x) => $x->kind)->map(fn($x) => $x->kind);
        $this->assertEquals('cat', $fourLegsAlphabetically->first());
        $this->assertEqualsCanonicalizing($wanted, $fourLegsAlphabetically->toArray());
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
        $original = [1, 1, 1, 1];
        $result = FunQuery::create($original)->some();
        $this->assertTrue($result);
    }

    public function testSlice()
    {
        $wanted = [15, 16, 17];
        $original = $this->generator();
        $result = FunQuery::create($original)->slice(10, 50)->skip(5)->limit(3);
        $wantedJson = json_encode($wanted);
        $resultJson = json_encode($result);
        $this->assertEquals($wantedJson, $resultJson);
    }

    public function testGenerator()
    {
        $wanted = [0, 2, 4, 6, 8, 10, 12, 14, 16, 18];
        $result = FunQuery::create($this->generator())->filter(fn($x) => $x % 2 == 0)->limit(10)->toArray();
        $this->assertEquals($wanted, $result);
    }

    public function testGeneratorMultiplePasses()
    {
        $wanted1 = [0, 1, 2, 3];
        $wanted2 = [0, 1];
        $obj = FunQuery::create($this->generator());
        $result1 = $obj->limit(4)->toArray();
        $result2 = $obj->limit(2)->toArray();
        $this->assertEquals($wanted1, $result1);
        $this->assertEquals($wanted2, $result2);
    }

    public function testEach()
    {
        $array = [new stdClass(), new stdClass(), new stdClass()];
        $i = random_int(1, 1000);
        FunQuery::create($array)->each(fn($x) => $x->a = $i)->skip(1)->limit(1)->each(fn($x) => $x->a = 0);
        $this->assertEquals($i, $array[0]->a);
        $this->assertEquals(0, $array[1]->a);
        $this->assertEquals($i, $array[2]->a);
    }

    public function testFlat()
    {
        $wanted = [0, 1, 2, 'string', 'string2'];
        $input = [];
        $input[] = FunQuery::create($this->generator())->limit(3);
        $input[] = ['string', 'string2'];
        $result = FunQuery::create($input)->flat()->toArray();
        $this->assertEquals($wanted, $result);
    }

    private function generator()
    {
        for ($i = 0; ; $i++) {
            yield $i;
        }
    }

    public function testCount()
    {
        $input = ['a', 'b', 'v'];
        $result = FunQuery::create($input)->count();
        $this->assertEquals(3, $result);
    }

    public function testCountGenerator()
    {
        $result = FunQuery::create($this->generator())->limit(20)->count();
        $this->assertEquals(20, $result);
    }

    public function testReduce()
    {
        $input = ['a', 'b', 'c', 'd'];
        $result = FunQuery::create($input)->reduce(fn($a, $x) => $a . $x);
        $this->assertEquals('abcd', $result);
    }

    public function testReduceWithInit()
    {
        $input = ['a', 'b', 'c', 'd'];
        $result = FunQuery::create($input)->reduce(fn($a, $x) => $a . $x, '012');
        $this->assertEquals('012abcd', $result);
    }

    public function testReduceEmpty()
    {
        $this->expectException("MKrawczyk\FunQuery\Exceptions\FunQueryException");
        $this->expectExceptionMessage("No items to reduce, empty input.");
        $result = FunQuery::create([])->reduce(fn($a, $x) => $a . $x);
    }

    public function testReduceEmptyWithInit()
    {
        $init = "init value";
        $result = FunQuery::create([])->reduce(fn($a, $x) => $a . $x, $init);
        $this->assertEquals($init, $result);
    }

    public function testMapOmmited()
    {
        $executed = 0;
        $obj = FunQuery::create($this->generator())->map(function () use (&$executed) {
            $executed++;
        })->skip(5)->limit(10);
        $this->assertEquals(10, $obj->count());
        $this->assertEquals(0, $executed);
        $obj->toArray();
        $this->assertEquals(10, $executed);
    }

}
