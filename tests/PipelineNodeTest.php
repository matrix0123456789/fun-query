<?php


use MKrawczyk\FunQuery\PipelineNode;
use PHPUnit\Framework\TestCase;

class PipelineNodeTest extends TestCase
{
    public function testSample()
    {
        $data = [
            (object)['kind' => 'dog', 'legs' => 4],
            (object)['kind' => 'cat', 'legs' => 4],
            (object)['kind' => 'centipede', 'legs' => 100],
            (object)['kind' => 'chicken', 'legs' => 2],
        ];
        $wanted = ['cat', 'dog'];

        $fourLegsAlphabetically = PipelineNode::create($data)->filter(fn($x) => $x->legs === 4)->sort(fn($x) => $x->kind)->map(fn($x) => $x->kind)->toArray();
        $this->assertEqualsCanonicalizing($wanted, $fourLegsAlphabetically);
    }
}
