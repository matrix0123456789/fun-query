<?php


use MKrawczyk\FunQuery\IteratorNode;
use MKrawczyk\FunQuery\PipelineNode;
use PHPUnit\Framework\TestCase;

class IteratorNodeTest extends TestCase
{
    public function test__construct()
    {
        $generator = $this->generator();
        $node = new IteratorNode($generator);
        $expect=[1,2,3];
        $this->assertEqualsCanonicalizing($expect, $node->toArray());
    }
    public function testFactory()
{
    $generator = $this->generator();
    $node = PipelineNode::create($generator);
    $expect=[1,2,3];
    $this->assertEqualsCanonicalizing($expect, $node->toArray());
}

    private function generator()
    {
        yield 1;
        yield 2;
        yield 3;
    }
}
