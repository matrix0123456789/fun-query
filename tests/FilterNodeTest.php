<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\FilterNode;
use PHPUnit\Framework\TestCase;

class FilterNodeTest extends TestCase
{
    public function testAll()
    {
        $init = [1, 5, 6, 9, 18];
        $array = new ArrayNode($init);
        $obj = new FilterNode($array, fn($x) => $x % 2 == 0);
        $this->assertEqualsCanonicalizing([6, 18], $obj->toArray());
    }
}
