<?php

namespace MKrawczyk\FunQuery;

/**
 * @template T
 * @template-extends T
 */
class ArrayNode extends FunQuery
{
    /**
     * @var T[]
     */
    private array $source;

    /**
     * @param T[] $source
     */
    public function __construct(array $source)
    {
        $this->source = $source;
    }

    /**
     * @return \ArrayIterator<int, T>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->source);
    }

}