<?php


namespace MKrawczyk\FunQuery;

/**
 * @template T
 * @template-extends FunQuery<T>
 */
class ConcatNode extends FunQuery
{
    /**
     * @var FunQuery<T>
     */
    private FunQuery $source1;

    /**
     * @var FunQuery<T>
     */
    private FunQuery $source2;


    /**
     * @param FunQuery<T> $source1
     * @param FunQuery<T> $source2
     *
     */
    public function __construct(FunQuery $source1, FunQuery $source2)
    {
        $this->source1 = $source1;
        $this->source2 = $source2;
    }

    public function getIterator(): SubConcatNode
    {
        return new SubConcatNode($this->source1->getIterator(), $this->source2->getIterator());
    }
}