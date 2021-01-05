<?php

namespace MKrawczyk\FunQuery;

abstract class FunQuery implements \IteratorAggregate, \JsonSerializable
{

    public static function create($init): FunQuery
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

    public function slice(int $skip = 0, ?int $limit = null)
    {
        return new SliceNode($this, $skip, $limit);
    }

    public function skip(int $skip = 0)
    {
        return new SliceNode($this, $skip);
    }

    public function limit(?int $limit = null)
    {
        return new SliceNode($this, 0, $limit);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this as $element) {
            $result[] = $element;
        }
        return $result;
    }

    public function some(?callable $fun = null)
    {
        foreach ($this as $item) {
            if ($fun === null || $fun($item))
                return true;
        }
        return false;
    }
    public function first(?callable $fun = null){
        foreach ($this as $item) {
            if ($fun === null || $fun($item))
                return $item;
        }
        throw new \Exception("Zero items");
    }
    public function firstOrNull(?callable $fun = null){
        foreach ($this as $item) {
            if ($fun === null || $fun($item))
                return $item;
        }
        return null;
    }
}