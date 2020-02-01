<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\MapNode;
use PHPUnit\Framework\TestCase;

class MapNodeTest extends TestCase
{
    public function testAll()
    {
        $init = [1, 5, 6, 9, 18];
        $array = new ArrayNode($init);
        $obj = new MapNode($array, fn($x) => $x * 2);
        $this->assertEqualsCanonicalizing([2, 10, 12, 18, 36], $obj->toArray());
    }
}
