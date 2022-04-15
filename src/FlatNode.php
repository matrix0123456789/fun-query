<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

class FlatNode extends FunQuery
{
    /**
     * @var FunQuery
     */
    private FunQuery $source;

    public function __construct(FunQuery $source)
    {
        $this->source = $source;
    }

    /**
     * @inheritDoc
     */
    public function getIterator():SubFlatNode
    {
        return new SubFlatNode($this->source->getIterator());
    }
}