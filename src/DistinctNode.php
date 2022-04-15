<?php


namespace MKrawczyk\FunQuery;


class DistinctNode extends FunQuery
{
    /**
     * @var FunQuery
     */
    private FunQuery $source;

    private $result = null;

    public function __construct(FunQuery $source)
    {
        $this->source = $source;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \ArrayIterator
    {
        if ($this->result === null) {
            $sourceArray = $this->source->toArray();
            $this->result = array_unique($sourceArray);
        }
        return new \ArrayIterator($this->result);
    }
}