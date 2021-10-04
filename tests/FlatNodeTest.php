<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\FlatNode;
use MKrawczyk\FunQuery\MapNode;
use PHPUnit\Framework\TestCase;

class FlatNodeTest extends TestCase
{
    public function testAll()
    {
        $init = [[1, 2, 3], [4, 5, 6]];
        $array = new ArrayNode($init);
        $obj = new FlatNode($array);
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6], $obj->toArray());
    }

    public function testEmpty()
    {
        $init = [];
        $array = new ArrayNode($init);
        $obj = new FlatNode($array);
        $this->assertEqualsCanonicalizing([], $obj->toArray());
    }
}
