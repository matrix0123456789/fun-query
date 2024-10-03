<?php


namespace MKrawczyk\FunQuery;

/**
 * @template TSource
 * @template TResult
 * @template-implements \Iterator<TResult>
 */
class SubMapNode implements \Iterator
{
    /**
     * @var \Iterator<TSource>
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
     * @return TResult
     */
    public function current() :mixed
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
    public function next(): void
    {
        $this->source->next();
    }

    /**
     * @inheritDoc
     */
    public function key():mixed
    {
        return $this->source->key();
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->source->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->source->rewind();
    }
}
