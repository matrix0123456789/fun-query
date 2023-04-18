<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\SliceNode;
use MKrawczyk\FunQuery\FunQuery;
use PHPUnit\Framework\TestCase;

class SliceNodeTest extends TestCase
{
    public function testAll()
    {
        $init = [1, 2, 3, 5, 4, 6, 1, 2];
        $array = new ArrayNode($init);
        $obj = new SliceNode($array, 0, null);
        $this->assertEqualsCanonicalizing([1, 2, 3, 5, 4, 6, 1, 2], $obj->toArray());
        $obj2 = new SliceNode($array, 2, null);
        $this->assertEqualsCanonicalizing([3, 5, 4, 6, 1, 2], $obj2->toArray());
        $obj3 = new SliceNode($array, 0, 5);
        $this->assertEqualsCanonicalizing([1, 2, 3, 5, 4], $obj3->toArray());
        $obj4 = new SliceNode($array, -3, null);
        $this->assertEqualsCanonicalizing([6, 1, 2], $obj4->toArray());
        $obj5 = new SliceNode($array, -3, -2);
        $this->assertEqualsCanonicalizing([6], $obj5->toArray());
        $obj6 = new SliceNode($array, 0, -5);
        $this->assertEqualsCanonicalizing([1, 2, 3], $obj6->toArray());
    }

    public function testGenerator()
    {
        $init = $this->generator();
        $obj0 = FunQuery::create($init);
        $obj = new SliceNode($obj0, 0, 3);
        $this->assertEqualsCanonicalizing([0, 1, 2], $obj->toArray());
    }

    public function testGenerator2()
    {
        $init = $this->generator();
        $obj0 = FunQuery::create($init);
        $obj = new SliceNode($obj0, 0, 3);
        $this->assertEqualsCanonicalizing([0, 1, 2], $obj->toArray());
    }

    private function generator()
    {
        for ($i = 0; ; $i++) {
            yield $i;
        }
    }

}
