<?php


namespace MKrawczyk\FunQuery;


class SortNode extends FunQuery
{
    /**
     * @var FunQuery
     */
    private FunQuery $source;
    /**
     * @var callable
     */
    private $funs;
    private $result = null;

    public function __construct(FunQuery $source, ...$funs)
    {
        $this->source = $source;
        $this->funs = $funs;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \ArrayIterator
    {
        $funs = $this->funs;
        if ($this->result === null) {
            $sourceArray = $this->source->toArray();
            usort($sourceArray, function ($a, $b) use ($funs) {
                $res = 0;
                foreach ($funs as $fun) {
                    $res = $fun($a) <=> $fun($b);
                    if ($res != 0) return $res;
                }
                return ($a) <=> ($b);
            });
            $this->result = $sourceArray;
        }
        return new \ArrayIterator($this->result);
    }
}