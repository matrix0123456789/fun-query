<?php


use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\ConcatNode;
use MKrawczyk\FunQuery\DistinctNode;
use MKrawczyk\FunQuery\SortNode;
use PHPUnit\Framework\TestCase;

class ConcatNodeTest extends TestCase
{
    public function testAll()
    {
        $init1 = [1, 2, 3, 4];
        $array1 = new ArrayNode($init1);
        $init2 = ['a', 'b'];
        $array2 = new ArrayNode($init2);
        $obj = new ConcatNode($array1, $array2);
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 'a', 'b'], $obj->toArray());
    }

    public function testToEmptyAll()
    {
        $init1 = [];
        $array1 = new ArrayNode($init1);
        $init2 = [1,2,3];
        $array2 = new ArrayNode($init2);
        $obj = new ConcatNode($array1, $array2);
        $this->assertEqualsCanonicalizing([1, 2, 3], $obj->toArray());
    }

}
