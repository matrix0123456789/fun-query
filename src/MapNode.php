<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

class MapNode extends FunQuery
{
    /**
     * @var FunQuery
     */
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
    public function getIterator():SubMapNode
    {
        return new SubMapNode($this->source->getIterator(), $this->fun);
    }
}