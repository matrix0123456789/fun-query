<?php

namespace MKrawczyk\FunQuery;

abstract class PipelineNode implements \IteratorAggregate
{

    public static function create($init) : PipelineNode
    {
        if (is_array($init)) {
            return new ArrayNode($init);
        } else if ($init instanceof \IteratorAggregate) {
            return new IteratorAggregateNode($init);
        } else if ($init instanceof \Iterator) {
            return new IteratorNode($init);
        } else {
            throw new \InvalidArgumentException("Source has invalid type.");
        }
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this as $element) {
            $result[] = $element;
        }
        return $result;
    }

    public function filter(callable $fun)
    {
        return new FilterNode($this, $fun);
    }

    public function sort(callable $fun)
    {
        return new SortNode($this, $fun);
    }

    public function map(callable $fun)
    {
        return new MapNode($this, $fun);
    }
}