<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\ReverseNode;
use MKrawczyk\FunQuery\SortNode;
use PHPUnit\Framework\TestCase;

class ReverseNodeTest extends TestCase
{
    public function testAll()
    {
        $init = ['a', 'b', 'c', 'd'];
        $array = new ArrayNode($init);
        $obj = new ReverseNode($array);
        $this->assertEqualsCanonicalizing(['d', 'c', 'b', 'a'], $obj->toArray());
    }

    public function testEmpty()
    {
        $init = [];
        $array = new ArrayNode($init);
        $obj = new ReverseNode($array);
        $this->assertEqualsCanonicalizing([], $obj->toArray());
    }

}