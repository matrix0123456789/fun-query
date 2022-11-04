<?php


namespace MKrawczyk\FunQuery;

/**
 * @template T
 * @template-extends FunQuery<T>
 */
class DistinctNode extends FunQuery
{
    /**
     * @var FunQuery<T>
     */
    private FunQuery $source;

    private $result = null;

    /**
     * @param FunQuery<T> $source
     */
    public function __construct(FunQuery $source)
    {
        $this->source = $source;
    }

    /**
     * @return \ArrayIterator<T>
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