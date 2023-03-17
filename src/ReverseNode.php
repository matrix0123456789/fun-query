<?php


namespace MKrawczyk\FunQuery;


use Exception;
use Traversable;

class ReverseNode extends FunQuery
{
    private FunQuery $source;
    private ?array $cache = null;
    private bool $executed = false;

    public function __construct(FunQuery $source)
    {
        $this->source = $source;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        if (!$this->executed) {
            $this->cache = array_reverse($this->source->toArray());
            $this->executed = true;
        }
        return new \ArrayIterator($this->cache);
    }
}