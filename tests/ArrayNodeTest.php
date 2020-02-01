<?php

use MKrawczyk\FunQuery\ArrayNode;
use MKrawczyk\FunQuery\PipelineNode;

include_once __DIR__.'/../vendor/autoload.php';


class ArrayNodeTest extends PHPUnit\Framework\TestCase
{
    public function testForEach()
    {
        $init = ['test', 'array', 'for', 'constructor'];
        $obj = new ArrayNode($init);
        $result = [];
        foreach ($obj as $element) {
            $result[] = $element;
        }
        $this->assertEqualsCanonicalizing($init, $result);
    }
    public function testToArray()
{
    $init = ['test', 'array', 'for', 'constructor'];
    $obj = new ArrayNode($init);
    $result =$obj->toArray();
    $this->assertEqualsCanonicalizing($init, $result);
}

    public function testFactoryConstruct()
    {
        $init = ['test', 'array', 'for', 'constructor'];
        $obj = PipelineNode::create($init);
        $this->assertInstanceOf(ArrayNode::class, $obj);
    }
}
