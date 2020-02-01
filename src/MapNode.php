<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

class MapNode extends PipelineNode
{
    /**
     * @var PipelineNode
     */
    private PipelineNode $source;
    /**
     * @var callable
     */
    private $fun;

    public function __construct(PipelineNode $source, callable $fun)
    {
        $this->source = $source;
        $this->fun = $fun;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new SubMapNode($this->source->getIterator(), $this->fun);
    }
}