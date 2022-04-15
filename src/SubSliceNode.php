<?php


namespace MKrawczyk\FunQuery;


class SubSliceNode implements \Iterator
{
    /**
     * @var \Iterator
     */
    private \Iterator $source;
    private int $skip;
    private ?int $limit;
    private ?int $position = null;

    public function __construct(\Iterator $source, int $skip = 0, ?int $limit = 0)
    {
        $this->source = $source;
        $this->skip = $skip;
        $this->limit = $limit;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $this->init();
        return $this->source->current();
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->init();
        $this->position++;
        if ($this->limit === null || $this->position < $this->limit) {
            $this->source->next();
        }

    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        $this->init();
        return $this->source->key();
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        $this->init();
        return $this->source->valid() && ($this->limit === null || $this->position < $this->limit);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->source->rewind();
        $this->position = null;
    }

    private function init()
    {
        if ($this->position === null) {
            for ($i = 0; $i < $this->skip; $i++) {
                $this->source->next();
            }
            $this->position = 0;
        }
    }
}