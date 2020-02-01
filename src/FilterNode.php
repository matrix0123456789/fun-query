<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

class FilterNode extends PipelineNode
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
        return new SubFilterNode($this->source->getIterator(), $this->fun);
    }
}