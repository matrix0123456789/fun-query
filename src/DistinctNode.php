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
    public function __construct(FunQuery $source, callable $fun = null)
    {
        $this->source = $source;
        $this->fun = $fun;
    }

    /**
     * @return \ArrayIterator<T>
     */
    public function getIterator(): \ArrayIterator
    {
        if ($this->result === null) {
            if($this->fun === null) {
                $sourceArray = $this->source->toArray();
                $this->result = array_unique($sourceArray, SORT_REGULAR);
            } else {
                $map= [];
                $fun=$this->fun;
                foreach ($this->source as $x){
                    $key=$fun($x);
                    if(!isset($map[$key])){
                        $map[$key]=$x;
                    }
                }
                $this->result = array_values($map);
            }
        }
        return new \ArrayIterator($this->result);
    }
}
