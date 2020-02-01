<?php


use MKrawczyk\FunQuery\IteratorAggregateNode;
use MKrawczyk\FunQuery\FunQuery;
use PHPUnit\Framework\TestCase;

class myData implements IteratorAggregate
{
    public $property1 = "one";
    public $property2 = "two";
    public $property3 = "three";

    public function __construct()
    {
        $this->property4 = "four";
    }

    public function getIterator()
    {
        return new ArrayIterator($this);
    }
}

class IteratorAggregateNodeTest extends TestCase
{

    public function test__construct()
    {
        $iterable = (new myData());
        $node = new IteratorAggregateNode($iterable);
        $expect = ["one", "two", "three", "four"];
        $this->assertEqualsCanonicalizing($expect, $node->toArray());
    }

    public function testFactory()
    {
        $iterable = (new myData());
        $node = FunQuery::create($iterable);
        $expect = ["one", "two", "three", "four"];
        $this->assertEqualsCanonicalizing($expect, $node->toArray());
    }
}
