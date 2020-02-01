<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\SortNode;
use PHPUnit\Framework\TestCase;

class SortNodeTest extends TestCase
{
    public function testAll()
    {
        $init = [1, 5, 9, -3];
        $array = new ArrayNode($init);
        $obj = new SortNode($array, fn($x) => $x);
        $this->assertEqualsCanonicalizing([-3, 1, 5, 9], $obj->toArray());
        $obj2 = new SortNode($array, fn($x) => \abs($x));
        $this->assertEqualsCanonicalizing([1, -3, 5, 9], $obj2->toArray());
    }
}