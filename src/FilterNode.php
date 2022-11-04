<?php


namespace MKrawczyk\FunQuery;


use Closure;
use Exception;
use Traversable;

/**
 * @template T
 * @template-extends FunQuery<T>
 */
class FilterNode extends FunQuery
{
    /**
     * @var FunQuery<T>
     */
    private FunQuery $source;
    /**
     * @var callable
     */
    private $fun;

    /**
     * @param FunQuery<T> $source
     * @param callable(T):bool $fun
     */
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