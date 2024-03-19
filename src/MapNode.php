<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

/**
 * @template TSource
 * @template TResult
 * @template-extends FunQuery<TResult>
 */
class MapNode extends FunQuery
{
    /**
     * @var FunQuery<TSource>
     */
    private FunQuery $source;
    /**
     * @var callable(TSource):TResult
     */
    private $fun;

    /**
     * @param FunQuery<TSource> $source
     * @param callable(TSource):TResult $fun
     */
    public function __construct(FunQuery $source, callable $fun)
    {
        $this->source = $source;
        $this->fun = $fun;
    }

    /**
     * @return SubMapNode<TSource, TResult>
     */
    public function getIterator(): SubMapNode
    {
        return new SubMapNode($this->source->getIterator(), $this->fun);
    }
}
