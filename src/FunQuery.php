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
     * @param array<T2>|IteratorAggregateNode<T2>|IteratorNode<T2>|FunQuery<T2> $init
     * @return FunQuery<T2>
     */
    public static function create($init): FunQuery
    {
        if ($init instanceof FunQuery) {
            return $init;
        } else if (is_array($init)) {
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
     * @param callable ...$funs
     * @return FunQuery
     */
    public function sort(...$funs)
    {
        return new SortNode($this, ...$funs);
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

    /**
     * @template TKey
     * @template TValue
     *
     * @param callable(T):TKey $keyFun
     * @param (callable(T):TValue)|null $valueFun
     * @return array<TKey, TValue>
     * @throws FunQueryException
     */
    public function toAssocArrayIgnoreDuplicates(callable $keyFun, ?callable $valueFun = null): array
    {
        $result = [];
        foreach ($this as $element) {
            $key = $keyFun($element);
            if (isset($result[$key])) {
                continue;
            }
            $value = $valueFun == null ? $element : $valueFun($element);
            $result[$key] = $value;
        }
        return $result;
    }


    /**
     * @param ?callable(T):bool $fun
     */
    public function some(?callable $fun = null): bool
    {
        foreach ($this as $item) {
            if ($fun === null || $fun($item))
                return true;
        }
        return false;
    }

    /**
     * @param callable(T):bool $fun
     */
    public function every(callable $fun): bool
    {
        foreach ($this as $item) {
            if (!$fun($item))
                return false;
        }
        return true;
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

    public function reverse()
    {
        return new ReverseNode($this);
    }

    public function execute()
    {
        return new ArrayNode($this->toArray());
    }

    /**
     * @return T
     */
    public function min(?callable $fun = null)
    {
        $min = null;
        $minF = null;
        $any = false;
        if ($fun == null)
            $fun = fn($x) => $x;
        foreach ($this as $item) {
            if ($any) {
                $f = $fun($item);
                if ($f < $minF) {
                    $min = $item;
                    $minF = $f;
                }
            } else {
                $any = true;
                $min = $item;
                $minF = $fun($item);
            }
        }
        if (!$any)
            throw new FunQueryException("No items to find minimum, empty input.");
        else return $min;
    }

    /**
     * @return T
     */
    public function max(?callable $fun = null)
    {
        $max = null;
        $maxF = null;
        $any = false;
        if ($fun == null)
            $fun = fn($x) => $x;
        foreach ($this as $item) {
            if ($any) {
                $f = $fun($item);
                if ($f > $maxF) {
                    $max = $item;
                    $maxF = $f;
                }
            } else {
                $any = true;
                $max = $item;
                $maxF = $fun($item);
            }
        }
        if (!$any)
            throw new FunQueryException("No items to find maximum, empty input.");
        else return $max;
    }

    public function sum(?callable $fun = null)
    {
        if ($fun == null)
            $fun = fn($x) => $x;
        return $this->map($fun)->reduce(fn($a, $b) => $a + $b, 0);
    }

    public function average(?callable $fun = null)
    {
        return $this->sum($fun) / $this->count();
    }


    // aliases

    /**
     * @alias create
     */
    public static function from($init): FunQuery
    {
        return self::create($init);
    }

    /**
     * @alias map
     */
    public function select(callable $fun): FunQuery
    {
        return $this->map($fun);
    }

    /**
     * @alias filter
     */
    public function where(callable $fun): FunQuery
    {
        return $this->filter($fun);
    }

    /**
     * @alias reduce
     */
    public function aggregate(callable $fun, ...$init)
    {
        return $this->reduce($fun, ...$init);
    }

    /**
     * @alias reduce
     */
    public function fold(callable $fun, ...$init)
    {
        return $this->reduce($fun, ...$init);
    }

    /**
     * @alias every
     */
    public function all(callable $fun)
    {
        return $this->every($fun);
    }

    /**
     * @alias some
     */
    public function any(callable $fun)
    {
        return $this->some($fun);
    }

}