<?php

namespace MKrawczyk\FunQuery;

class ArrayNode extends FunQuery
{
    /**
     * @var array
     */
    private array $source;

    public function __construct(array $source)
    {
        $this->source = $source;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->source);
    }

}