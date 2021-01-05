<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

class SliceNode extends FunQuery
{
    private FunQuery $source;
    private int $skip;
    private ?int $limit;

    public function __construct(FunQuery $source, int $skip=0, ?int $limit=null)
    {
        $this->source = $source;
        $this->skip = $skip;
        $this->limit = $limit;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        if($this->skip<0 || $this->limit < 0){
            $array=$this->source->toArray();
            return new \ArrayIterator(array_slice($array,$this->skip, $this->limit));
        }else {
            return new SubSliceNode($this->source->getIterator(), $this->skip, $this->limit);
        }
    }
}