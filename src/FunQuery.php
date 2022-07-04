<?php

namespace MKrawczyk\FunQuery;

use Iterator;
use IteratorAggregate;
use MKrawczyk\FunQuery\Exceptions\FunQueryException;
use Traversable;

/**
 * @template T
 */
abstract class FunQuery implements IteratorAggregate, \JsonSerializable
{
    /**
     * @template T2
     * @param T2 $init
     * @return FunQuery<T2>
     */
    public static function create($init): FunQuery
    {
        if (is_array($init)) {
            return new ArrayNode($init);
        } else if ($init instanceof IteratorAggregate) {
            return new IteratorAggregateNode($init);
        } else if ($init instanceof Iterator) {
            return new IteratorNode($init);
        } else {
            throw new \InvalidArgumentException("Source has invalid type.");
        }
    }

    /**
     * @return FunQuery<T>
     */
    public function filter(callable $fun)
    {
        return new FilterNode($this, $fun);
    }

    /**
     * @return FunQuery<T>
     */
    public function sort(callable $fun)
    {
        return new SortNode($this, $fun);
    }

    /**
     * @template T2
     * @param callable(T):T2 $fun
     * @return FunQuery<T2>
     */
    public function map(callable $fun)
    {
        return new MapNode($this, $fun);
    }

    /**
     * @return FunQuery<T>
     */
    public function slice(int $skip = 0, ?int $limit = null)
    {
        return new SliceNode($this, $skip, $limit);
    }

    /**
     * @return FunQuery<T>
     */
    public function skip(int $skip = 0)
    {
        return new SliceNode($this, $skip);
    }

    /**
     * @return FunQuery<T>
     */
    public function limit(?int $limit = null)
    {
        return new SliceNode($this, 0, $limit);
    }

    public function flat()
    {
        return new FlatNode($this);
    }

    /**
     * @return FunQuery<T>
     */
    public function distinct()
    {
        return new DistinctNode($this);
    }

    public function flatMap(callable $fun)
    {
        return new FlatNode(new MapNode($this, $fun));
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array<mixed, T>
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this as $element) {
            $result[] = $element;
        }
        return $result;
    }

    /**
     * @template TKey
     * @template TValue
     *
     * @param callable(T):TKey $keyFun
     * @param (callable(T):TValue)|null $valueFun
     * @return array<TKey, TValue>
     * @throws FunQueryException
     */
    public function toAssocArray(callable $keyFun, ?callable $valueFun = null): array
    {
        $result = [];
        foreach ($this as $element) {
            $key = $keyFun($element);
            if (isset($result[$key])) {
                throw new FunQueryException("Duplicated keys");
            }
            $value = $valueFun == null ? $element : $valueFun($element);
            $result[$key] = $value;
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

    /**
     * @return T
     */
    public function first(?callable $fun = null)
    {
        foreach ($this as $item) {
            if ($fun === null || $fun($item))
                return $item;
        }
        throw new \Exception("Zero items");
    }

    /**
     * @return T|null
     */
    public function firstOrNull(?callable $fun = null)
    {
        foreach ($this as $item) {
            if ($fun === null || $fun($item))
                return $item;
        }
        return null;
    }

    public function __debugInfo()
    {
        return $this->toArray();
    }

    public function each(callable $fun)
    {
        foreach ($this as $x) {
            $fun($x);
        }
        return $this;
    }

    public function count(): int
    {
        $ret = 0;
        $iterator = $this->getIterator();
        while ($iterator->valid()) {
            $ret++;
            $iterator->next();
        }
        return $ret;
    }

    public function reduce(callable $fun, ...$init)
    {
        $iterator = $this->getIterator();
        if (isset($init[0])) {
            $value = $init[0];
        } else if ($iterator->valid()) {
            $value = $iterator->current();
            $iterator->next();
        } else {
            throw new FunQueryException("No items to reduce, empty input.");
        }
        while ($iterator->valid()) {
            $value = $fun($value, $iterator->current());
            $iterator->next();
        }
        return $value;
    }

    public function groupBy(\Closure $fun)
    {
        $items = [];
        foreach ($this->groupAssoc($fun) as $key => $values) {
            $items[] = new Group($key, $values);
        }
        return new ArrayNode($items);
    }

    public function groupAssoc(\Closure $fun)
    {
        $items = [];
        foreach ($this as $x) {
            $key = $fun($x);
            if (!isset($items[$key])) {
                $items[$key] = [];
            }
            $items[$key][] = $x;
        }
        return $items;
    }
}