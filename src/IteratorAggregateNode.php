<?php


namespace MKrawczyk\FunQuery;

use IteratorAggregate;

class IteratorAggregateNode extends FunQuery
{
    /**
     * @var IteratorAggregate
     */
    private IteratorAggregate $source;

    public function __construct(IteratorAggregate $source)
    {
        $this->source = $source;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \Traversable
    {
        return $this->source->getIterator();
    }
}