<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

class FilterNode extends FunQuery
{
    private FunQuery $source;
    /**
     * @var callable
     */
    private $fun;

    public function __construct(FunQuery $source, callable $fun)
    {
        $this->source = $source;
        $this->fun = $fun;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): SubFilterNode
    {
        return new SubFilterNode($this->source->getIterator(), $this->fun);
    }
}