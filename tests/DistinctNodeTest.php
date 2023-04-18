<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\DistinctNode;
use MKrawczyk\FunQuery\SortNode;
use PHPUnit\Framework\TestCase;

class DistinctNodeTest extends TestCase
{
    public function testAll()
    {
        $init = [1, 2, 5, 9, 2, 1, 1];
        $array = new ArrayNode($init);
        $obj = new DistinctNode($array);
        $this->assertEqualsCanonicalizing([1, 2, 5, 9], $obj->toArray());
    }

}