<?php


namespace MKrawczyk\FunQuery;


class SortNode extends FunQuery
{
    /**
     * @var FunQuery
     */
    private FunQuery $source;
    /**
     * @var callable
     */
    private $fun;
    private $result = null;

    public function __construct(FunQuery $source, callable $fun)
    {
        $this->source = $source;
        $this->fun = $fun;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \ArrayIterator
    {
        $fun = $this->fun;
        if ($this->result === null) {
            $sourceArray = $this->source->toArray();
            usort($sourceArray, fn($a, $b) => $fun($a) <=> $fun($b));
            $this->result = $sourceArray;
        }
        return new \ArrayIterator($this->result);
    }
}