<?php


namespace MKrawczyk\FunQuery;


class SubMapNode implements \Iterator
{
    /**
     * @var \Iterator
     */
    private \Iterator $source;
    /**
     * @var callable
     */
    private $fun;

    public function __construct(\Iterator $source, callable $fun)
    {
        $this->source = $source;
        $this->fun = $fun;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $fun = $this->fun;
        if ($this->valid())
            return $fun($this->source->current());
        else
            return null;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->source->next();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->source->key();
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->source->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->source->rewind();
    }
}